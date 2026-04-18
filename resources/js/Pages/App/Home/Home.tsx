import {FC, ReactNode} from "react";
import Page from "../components/Page/Page.tsx";
import useTranslations from "../../../hooks/useTranslations.tsx";
import './home.css';
import {Link} from "@inertiajs/react";
import {useRoutes} from "../../../hooks/useRoutes.ts";

const Home: FC = (): ReactNode => {

    const __ = useTranslations();
    const routes = useRoutes();

    return (
        <Page browserTitle={__('home.browser_title')} className={'home'}>
            <section className="py-5">
                <div className="container">
                    <h1 className="display-5 fw-bold mb-4">FakeData</h1>

                    <div className="row g-4">
                        <div className="col-12 col-md-6 col-lg-4">
                            <div className="card h-100">
                                <div className="card-body d-flex flex-column">
                                    <h2 className="h4 card-title">Codice fiscale</h2>
                                    <p className="card-text text-muted flex-grow-1">
                                        Genera un’anagrafica casuale con i dati pronti da copiare.
                                    </p>
                                    <Link className="btn btn-primary" href={routes.app.codiceFiscale()}>
                                        Vai alla pagina dedicata
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <div className="col-12 col-md-6 col-lg-4">
                            <div className="card h-100">
                                <div className="card-body d-flex flex-column">
                                    <h2 className="h4 card-title">Partita IVA</h2>
                                    <p className="card-text text-muted flex-grow-1">
                                        Coming soon.
                                    </p>
                                    <button className="btn btn-outline-secondary" type="button" disabled>
                                        Coming soon
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </Page>
    );
}

export default Home;
