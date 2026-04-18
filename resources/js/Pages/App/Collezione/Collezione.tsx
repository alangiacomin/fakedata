import {FC, ReactNode} from "react";
import Page from "../components/Page/Page.tsx";
import useTranslations from "../../../hooks/useTranslations.tsx";
import {Link} from "@inertiajs/react";
import {useRoutes} from "../../../hooks/useRoutes.ts";

const Collezione: FC = (): ReactNode => {

    const __ = useTranslations();
    const routes = useRoutes();

    return (
        <Page browserTitle={__('collezione.title')}>
            <div className="container text-center py-5">
                <div className="row justify-content-center">
                    <div className="col-lg-8">
                        <i className="bi bi-shield-lock display-1 text-primary mb-4"></i>
                        <h2 className="display-4 fw-bold">Gestisci la tua collezione</h2>
                        <p className="lead text-muted mb-5">Questa sezione è riservata ai collezionisti loggati. Accedi
                            per iniziare
                            a tracciare i tuoi fumetti e non perdere più un numero.</p>

                        <div className="row g-4 text-start mb-5">
                            <div className="col-md-6">
                                <div className="d-flex">
                                    <i className="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                                    <div>
                                        <h5>Mancanti in tempo reale</h5>
                                        <p className="text-muted">Visualizza subito quali numeri ti mancano per
                                            completare la
                                            serie.</p>
                                    </div>
                                </div>
                            </div>
                            <div className="col-md-6">
                                <div className="d-flex">
                                    <i className="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                                    <div>
                                        <h5>Edizioni & Ristampe</h5>
                                        <p className="text-muted">Gestisci separatamente l'inedito, la ristampa o
                                            l'edizione
                                            speciale.</p>
                                    </div>
                                </div>
                            </div>
                            <div className="col-md-6">
                                <div className="d-flex">
                                    <i className="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                                    <div>
                                        <h5>Statistiche di possesso</h5>
                                        <p className="text-muted">Monitora la percentuale di completamento di ogni
                                            testata.</p>
                                    </div>
                                </div>
                            </div>
                            <div className="col-md-6">
                                <div className="d-flex">
                                    <i className="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                                    <div>
                                        <h5>Cloud Sync</h5>
                                        <p className="text-muted">Accedi alla tua collezione da qualsiasi
                                            dispositivo.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="card bg-dark text-white p-4 mb-4">
                            <p className="mb-3">Effettua il Login per iniziare a tracciare la tua collezione
                                personale.</p>
                            <div className="d-flex justify-content-center">
                                <Link className="btn btn-outline-light" href={routes.app.login()}>
                                    {__('login')}
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Page>
    );
};

// noinspection JSUnusedGlobalSymbols
export default Collezione;
