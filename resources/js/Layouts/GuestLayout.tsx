import ApplicationLogo from '@/Components/ApplicationLogo';
import { Link } from '@inertiajs/react';
import { PropsWithChildren } from 'react';
import { Header } from '../Components/Header';

export default function Guest({ children }: PropsWithChildren) {
    return (
        <div>
            <div className="h-screen">
                <Header/>
                <div className="flex flex-col sm:justify-center items-center pt-6 mt-36 sm:pt-0 bg-gray-100">
                    <div className="w-full sm:max-w-md mt-6 px-6 py-4 bg-box-color shadow-md overflow-hidden sm:rounded-lg">
                        {children}
                    </div>
                </div>
            </div>
        </div>
    );
}
