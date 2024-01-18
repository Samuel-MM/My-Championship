import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';
import { PageProps } from '@/types';
import PrimaryButton from '../Components/PrimaryButton';

export default function Dashboard({ auth }: PageProps) {
    return (
        <div>
            <AuthenticatedLayout
                user={auth.user}
                header={<h2 className="font-bold text-5xl flex justify-center mt-24 text-font-color">Olá, {auth.user.name} </h2>}
            >
                <div className='text-center mt-24'>
                    <PrimaryButton className="ms-4 mx-auto">
                        <Link href={route('championship')}>Novo Campeonato</Link>
                    </PrimaryButton>
                </div>
            </AuthenticatedLayout>
        </div>
    );
}
