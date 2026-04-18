import {FC, ReactNode} from "react";
import Navbar from "../Navbar/Navbar.tsx";
import {useRoutes} from "../../../../hooks/useRoutes.ts";
import {usePage} from "@inertiajs/react";
import {SharedPageProps} from "../../../page.types.ts";

type LayoutProps = {
    children: ReactNode;
}
const Layout: FC<LayoutProps> = ({children}: LayoutProps): ReactNode => {
    const routes = useRoutes();
    const {props} = usePage<SharedPageProps>();
    const currentLocale = props.locale || props.defaultLocale;

    const languages = [
        {value: '', label: 'IT'},
        {value: 'en', label: 'EN'},
        {value: 'fr', label: 'FR'},
    ];

    return (
        <div className="d-flex flex-column min-vh-100">
            <Navbar/>
            <main className="">
                {children}
            </main>

            <footer className="bg-dark text-white py-4 mt-auto">
                <div className="container text-center">
                    <p className="mb-0">&copy; 2026 Company Inc.</p>
                </div>
            </footer>
        </div>
    );
}

export default Layout;
