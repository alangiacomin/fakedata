import {FC, ReactNode, useState} from "react";
import classNames from "classnames";
import NavbarItem from "./NavbarItem.tsx";
import {Link, usePage} from "@inertiajs/react";
import NavbarDropdown from "./NavbarDropdown.tsx";
import NavbarDropdownItem from "./NavbarDropdownItem.tsx";
import {useRoutes} from "../../../../hooks/useRoutes.ts";
import useAppData from "../../../../hooks/useAppData.tsx";
import {SharedPageProps} from "../../../page.types.ts";

const Navbar: FC = (): ReactNode => {
    const [isBurgerOpen, setIsBurgerOpen] = useState(false);
    const routes = useRoutes();
    const {appName} = useAppData();
    const {props} = usePage<SharedPageProps>();
    const currentLocale = props.locale || props.defaultLocale;

    const toggleBurger = () => {
        setIsBurgerOpen(!isBurgerOpen);
    };
    const closeBurger = () => {
        setIsBurgerOpen(false);
    }

    const navConfig: { [key: string]: { path: string, label: string } } = {
        codiceFiscale: {
            path: routes.app.codiceFiscale(),
            label: 'Codice fiscale',
        },
    };

    const locales = props.locales.map((locale) => ({value: locale, label: locale.toUpperCase()}));

    const changeLocale = (e: MouseEvent, locale: string) => {
        e.preventDefault();
        routes.changeLocale(locale);
    }

    return (
        <nav className="navbar navbar-expand-lg navbar-dark bg-dark">
            <div className="container">
                <Link className="navbar-brand fw-bold text-uppercase" href={routes.app.home()}>{appName}</Link>
                <button className="navbar-toggler" type="button" onClick={toggleBurger}>
                    <span className="navbar-toggler-icon"></span>
                </button>
                <div className={classNames("navbar-collapse", {"collapse": !isBurgerOpen})} id="navbarSupportedContent">
                    <ul className="navbar-nav me-auto mb-2 mb-lg-0">
                        {Object.entries(navConfig).map(([key, n]) => {
                            return (
                                <NavbarItem key={key} to={n.path} onClick={closeBurger}>
                                    {n.label}
                                </NavbarItem>
                            );
                        })}
                    </ul>
                    {locales && locales.length > 1 && (
                        <ul className="navbar-nav ms-auto mb-2 mb-lg-0">
                            <NavbarDropdown className={'me-3'} label={(
                                <span className={'text-white'}>
                            <i className="bi bi-globe2"></i> {currentLocale.toUpperCase()}
                        </span>
                            )}>
                                {locales.map((l, index) => (
                                    <NavbarDropdownItem key={index} to={'#'} onClick={(e) => {
                                        changeLocale(e, l.value)
                                    }}>
                                        {l.label} ({l.value.toUpperCase()})
                                    </NavbarDropdownItem>
                                ))}
                            </NavbarDropdown>
                        </ul>
                    )}
                </div>
            </div>
        </nav>
    );
}

export default Navbar;
