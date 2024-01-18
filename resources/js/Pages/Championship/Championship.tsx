import { FormEventHandler } from 'react';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import { Head, Link, useForm } from '@inertiajs/react';
import AuthenticatedLayout from '../../Layouts/AuthenticatedLayout';
import { PageProps } from '../../types';

export default function Championship({ auth }: PageProps) {
  const { data, setData, post, errors, reset } = useForm({
    title: '',
    teams: Array.from({ length: 8 }, () => ''),
  });

  const submit: FormEventHandler = async (e) => {
    e.preventDefault();
    try{
      post(route('championship.new'), {
        data,
      });
      
    } catch (error) {
      console.error('Error submitting form:', error);
      return;
    }
    
  };

  return (
    <div className='bg-background-color'>
      <AuthenticatedLayout user={auth.user}>
        <h2 className="font-bold text-5xl flex justify-center mt-24 text-font-color">Novo Campeonato</h2>
        <Head title="Campeonato"/>
        {status && <div className="mb-10 font-medium text-sm text-green-600">{status}</div>}
        <div className="flex flex-col sm:justify-center items-center pt-6 mt-36 sm:pt-0 bg-gray-100">
          <div className="w-full sm:max-w-md mb-6 px-6 py-4 bg-box-color shadow-md overflow-hidden sm:rounded-lg">
            <form onSubmit={submit}>
              <div className='mb-10'>
                <InputLabel htmlFor="title" value="Título do Campeonato" />
                <TextInput
                  id="title"
                  type="text"
                  name="title"
                  value={data.title}
                  className="mt-1 block w-full text-black"
                  autoComplete="title"
                  isFocused={true}
                  onChange={(e) => setData('title', e.target.value)}
                  required
                />
                <InputError message={errors.title} className="mt-2" />
              </div>
              <div>
                {data.teams.map((team, index) => (
                  <div key={index} className="mt-4 text-black">
                    <InputLabel htmlFor={`team-${index}`} value={`Time ${index + 1}`} />

                    <TextInput
                      id={`team-${index}`}
                      type="text"
                      name={`team-${index}`}
                      value={team}
                      className="mt-1 block w-full"
                      autoComplete={`current-team-${index}`}
                      onChange={(e) => {
                        const updatedTeams = [...data.teams];
                        updatedTeams[index] = e.target.value;
                        setData('teams', updatedTeams);
                      }}
                      required
                    />

                  </div>
                ))}
              </div>
            <div className='text-center mt-5'>
              <PrimaryButton className="ms-4 mx-auto">
                <Link href={route('championship')}>Simular</Link>
              </PrimaryButton>
            </div>
            </form>
          </div>
        </div>
      </AuthenticatedLayout>
    </div>
  );
}
