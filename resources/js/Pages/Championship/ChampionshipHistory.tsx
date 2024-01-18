
import { Head } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';

export default function ChampionshipHistory({ auth, championshipData }) {

const { ...rounds } = championshipData;

return (
  <div className='bg-background-color text-center'>
    <AuthenticatedLayout user={auth.user}>
      <h2 className="font-bold text-5xl flex justify-center mt-24 text-font-color">Histórico</h2>
      <Head title="Histórico" />
      {Object.keys(rounds).map((roundIndex) => (
        <div key={roundIndex}>
          <h2 className="font-bold text-3xl flex justify-center mt-16 mb-4 text-font-color">{rounds[roundIndex][0].title}</h2>
          <div className="flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div className="w-full sm:max-w-md mb-6 px-6 py-4 bg-box-color shadow-md overflow-hidden sm:rounded-lg">
              <table className="w-full text-font-color text-2xl">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>GP</th>
                    <th>GC</th>
                    <th>Pontos</th>
                  </tr>
                </thead>
                <tbody>
                  {rounds[roundIndex].map((match, matchIndex) => (
                    <tr key={matchIndex} className='border-b mb-4'>
                      <td>{match.place}</td>
                      <td>{match.team_name}</td>
                      <td>{match.pro_goals_total}</td>
                      <td>{match.own_goals_total}</td>
                      <td>{match.points}</td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      ))}
    </AuthenticatedLayout>
  </div>
);
}