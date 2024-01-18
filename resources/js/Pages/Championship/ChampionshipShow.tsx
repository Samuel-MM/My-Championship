
import { Head } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';

export default function Championship({ auth, matchResults }) {

  const { championship_title, ...rounds } = matchResults;

  const roundTitles = ["Quartas de final", "Semi-Final", "Disputa de Terceiro Lugar", "Final"];

  return (
    <div className='bg-background-color text-center'>
      <AuthenticatedLayout user={auth.user}>
        <h2 className="font-bold text-5xl flex justify-center mt-24 text-font-color">{championship_title} - Resultados</h2>
        <Head title="Campeonato" />
        {Object.keys(rounds).map((roundIndex) => (
          <div key={roundIndex}>
            {roundTitles[Number(roundIndex)] && (
              <h3 className="text-2xl font-bold mt-6 text-font-color">{roundTitles[roundIndex]}</h3>
            )}
            <div className="flex flex-col sm:justify-center items-center pt-6 mt-8 sm:pt-0 bg-gray-100">
              <div className="w-full sm:max-w-md mb-6 px-6 py-4 bg-box-color shadow-md overflow-hidden sm:rounded-lg">
                {rounds[roundIndex].map((match, matchIndex) => (
                  <div key={matchIndex} className='flex justify-around text-xl mb-2'>
                    <p className='flex w-full'>
                      <span style={{ color: match.first_team.winner ? 'green' : 'red' }} className='w-1/3 text-justify'>{match.first_team.team_name}</span>
                      <span className='text-font-color flex w-1/3 justify-center'> {match.first_team.pro_goals} X {match.second_team.pro_goals} </span>
                      <span style={{ color: match.second_team.winner ? 'green' : 'red' }} className='w-1/3 text-end'>{match.second_team.team_name}</span>
                    </p>
                  </div>
                ))}
              </div>
            </div>
          </div>
        ))}
      </AuthenticatedLayout>
    </div>
  );
}