import { Link } from "@inertiajs/react";

export const Header = () => {
  return (
    <header className="bg-header-color text-font-color p-6 flex justify-between items-center">
      <div className="flex items-center">
        <span className="text-lg font-bold"><Link href={route('dashboard')}>Meu Campeonato</Link></span>
      </div>
      <div className="flex space-x-4">
        <span className="cursor-pointer"><Link href={route('register')}>Registrar</Link></span>
        <span className="cursor-pointer"><Link href={route('login')}>Login</Link></span>
      </div>
    </header>
  );
}