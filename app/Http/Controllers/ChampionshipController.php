<?php

namespace App\Http\Controllers;

use App\Models\Championship;
use App\Models\ChampionshipHistory;
use App\Models\Team;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChampionshipController extends Controller
{
    private $teamsData = [];
    private $firstGroup = [];
    private $secondGroup = [];
    private $finalTeams = [];
    private $results = [];
    private $maxGoals = 10;
    private $minGoals = 0;
    private $phase = 0;
    private $fourthPlace = 4;
    private $thirdPlace = 3;
    private $secondPlace = 2;
    private $firstPlace = 1;
    private $championshipId;

    public function show()
    {
        return Inertia::render('Championship/Championship');
    }

    public function showChampionshipHistory()
    {

        $championshipHistoryData = $this->getChampionshipHistory();
        $championshipData = $this->orderChampionshipHistoryData($championshipHistoryData);

        return inertia('Championship/ChampionshipHistory', ['championshipData' => $championshipData]);

        return response()->json($championshipData, 200);
    }

    public function store(Request $request)
    {
        $teamNames = $request->teams;
        $championshipTitle = $request->title;

        $this->storeChampionshipData($championshipTitle);
        $this->storeTeamData($teamNames);
        $this->executeChampionship();
        $this->storeChampionshipHistory();

        $this->results['championship_title'] = $championshipTitle;

        if ($request->header('X-Inertia')) {
            return inertia('Championship/ChampionshipShow', ['matchResults' => $this->results]);
        }

        return response()->json($this->results, 200);
    }

    private function executeChampionship()
    {
        while ($this->phase < 3) {
            $this->splitGroup();
            $matchesData = $this->matches();
            $winners = $this->getWinners($matchesData);
            switch ($this->phase) {
                case 1:
                    $this->firstGroup = [];
                    $this->secondGroup = [];
                    $losers = $this->getLosers($matchesData);
                    $this->firstGroup[0] = $losers[0];
                    $this->secondGroup[0] = $losers[1];
                    $thirdPlaceData = $this->matches();
                    $thirdPlaceWinner = $this->getWinners($thirdPlaceData);
                    foreach ($losers as &$loser) {
                        if ($loser['team_id'] == $thirdPlaceWinner[0]['team_id']) {
                            $loser['place'] = $this->thirdPlace;
                        } else {
                            $loser['place'] = $this->fourthPlace;
                        }
                    }
                    $this->finalTeams = $losers;
                    break;
                case 2:
                    $loserTeam = $this->getLosers($matchesData);
                    $winnerTeam = $winners;
                    $loserTeam[0]['place'] = $this->secondPlace;
                    $winnerTeam[0]['place'] = $this->firstPlace;
                    array_push($this->finalTeams, $loserTeam[0], $winnerTeam[0]);
                    break;
            }
            $this->teamsData = $winners;
            $this->phase++;
        }
    }

    private function storeTeamData($teamNames)
    {
        foreach ($teamNames as $teamName) {
            $newTeam = Team::create([
                'team_name' => $teamName
            ])->toArray();

            $this->teamsData[] = [
                'team_id' => $newTeam['id'],
                'team_name' => $newTeam['team_name']
            ];
        };
    }

    private function storeChampionshipData($championshipTitle)
    {
        $championship = Championship::create(['title' => $championshipTitle]);
        $this->championshipId = $championship->id;
    }

    private function storeChampionshipHistory()
    {
        foreach ($this->finalTeams as $teamData) {
            $teamData['championship_id'] = $this->championshipId;
            $teamHistory = new ChampionshipHistory($teamData);
            $teamHistory->save();
        }
    }

    private function getChampionshipHistory()
    {
        $data = Championship::with('teams')
            ->join('championship_histories', 'championships.id', '=', 'championship_histories.championship_id')
            ->join('teams', 'teams.id', '=', 'championship_histories.team_id')
            ->select(
                'championships.id as championship_id',
                'championships.title',
                'teams.team_name',
                'championship_histories.pro_goals_total',
                'championship_histories.own_goals_total',
                'championship_histories.points',
                'championship_histories.place'
            )
            ->orderBy('championships.id')
            ->orderBy('championship_histories.place', 'ASC')
            ->get();

        return $data->toArray();
    }

    private function splitGroup()
    {
        shuffle($this->teamsData);

        $totalTeams = count($this->teamsData);
        $middle = floor($totalTeams / 2);

        $this->firstGroup = array_slice($this->teamsData, 0, $middle);
        $this->secondGroup = array_slice($this->teamsData, $middle);
    }

    private function matches()
    {
        $matchesData = [];
        $numOfMatches = count($this->firstGroup);
        for ($match = 0; $match < $numOfMatches; $match++) {
            $firstTeamGoals = rand($this->minGoals, $this->maxGoals);
            $secondTeamGoals = rand($this->minGoals, $this->maxGoals);
            $matchData = [$firstTeamGoals, $secondTeamGoals];
            $matchesData[$match] = [
                'first_team' =>  $this->teamMatchData($this->firstGroup[$match], $this->secondGroup[$match], $matchData, 0),
                'second_team' => $this->teamMatchData($this->secondGroup[$match], $this->firstGroup[$match], $matchData, 1)
            ];
        }
        array_push($this->results, $matchesData);
        return $matchesData;
    }

    private function teamMatchData($mainTeam, $oppositeTeam, $matchData, $teamGoalOrder)
    {
        $teamId = $mainTeam['team_id'];
        $teamName = $mainTeam['team_name'];
        $proGoals = $matchData[$teamGoalOrder];
        $ownGoals = $matchData[!$teamGoalOrder];
        $proGoalsTotal = (!empty($mainTeam['pro_goals_total'])) ? $mainTeam['pro_goals_total'] + $matchData[$teamGoalOrder] : $matchData[$teamGoalOrder];
        $ownGoalsTotal = (!empty($mainTeam['own_goals_total'])) ? $mainTeam['own_goals_total'] + $matchData[!$teamGoalOrder] : $matchData[!$teamGoalOrder];
        $points = $proGoalsTotal - $ownGoalsTotal;
        $winner = ($matchData[$teamGoalOrder] > $matchData[!$teamGoalOrder]) ? True : False;
        if ($matchData[$teamGoalOrder] == $matchData[!$teamGoalOrder] && $teamGoalOrder) {
            $winner = True;
        }
        $opposingTeamId = $oppositeTeam['team_id'];
        $place = (!empty($mainTeam['place'])) ? $mainTeam['place'] : '';
        $matchData = [
            'team_id' => $teamId,
            'team_name' => $teamName,
            'pro_goals' => $proGoals,
            'pro_goals_total' => $proGoalsTotal,
            'own_goals' => $ownGoals,
            'own_goals_total' => $ownGoalsTotal,
            'points' => $points,
            'winner' => $winner,
            'opposing_team_id' => $opposingTeamId,
            'place' => $place
        ];

        return $matchData;
    }

    private function getWinners($matchesData)
    {
        $winners = [];

        foreach ($matchesData as $match) {
            if ($match['first_team']['winner']) {
                array_push($winners, $match['first_team']);
            } else if ($match['second_team']['winner']) {
                array_push($winners, $match['second_team']);
            } else {
                array_push($winners, $match['second_team']);
            }
        }
        return $winners;
    }

    private function getLosers($matchesData)
    {
        $losersTeams = [];
        foreach ($matchesData as $match) {
            if (!$match['first_team']['winner'] && $match['second_team']['winner']) {
                array_push($losersTeams, $match['first_team']);
            } else if (!$match['second_team']['winner'] && $match['first_team']['winner']) {
                array_push($losersTeams, $match['second_team']);
            } else {
                array_push($losersTeams, $match['first_team']);
            }
        }
        return $losersTeams;
    }

    private function orderChampionshipHistoryData($championshipData)
    {
        $groupedData = [];
        foreach ($championshipData as $item) {
            $championshipId = $item['championship_id'];

            if (!isset($groupedData[$championshipId])) {
                $groupedData[$championshipId] = [];
            }

            $groupedData[$championshipId][] = $item;
        }
        $groupedData = array_values($groupedData);

        return $groupedData;
    }

};
