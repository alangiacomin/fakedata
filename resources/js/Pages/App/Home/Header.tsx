import useTranslations from "../../../hooks/useTranslations.tsx";
import useAuth from "../../../hooks/useAuth.tsx";

const Header = () => {

    const __ = useTranslations();
    const user = useAuth();

    return (
        <header className="hero-section text-center">
            <div className="container">
                <h1 className="display-3 fw-bold mb-4">{__('home.head_title')}</h1>
                <p className="lead mb-5">{__('home.head_subtitle')}<br/>{__('home.head_subtitle2')}</p>
                <div className="d-grid gap-3 d-sm-flex justify-content-sm-center">
                    <a className="btn btn-primary btn-lg px-4 gap-3" href="/prototipo/testate.html">
                        {user ? __('home.vai_lista') : __('home.inizia_ora')}
                    </a>
                    <button className="btn btn-outline-light btn-lg px-4">{__('home.scopri')}</button>
                </div>
            </div>
        </header>);
}

export default Header;
