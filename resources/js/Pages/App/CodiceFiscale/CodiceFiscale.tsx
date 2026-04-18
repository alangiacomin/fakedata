import {FC, ReactNode, useMemo, useState} from "react";
import Page from "../components/Page/Page.tsx";
import {PersonaFisicaData} from "../../../types/generated";

const emptyPersona: PersonaFisicaData = {
    codiceFiscale: '',
    cognome: '',
    nome: '',
    sesso: '',
    dataNascita: '',
    comuneNascitaCodice: '',
    comuneNascitaDescrizione: '',
};

const CodiceFiscale: FC = (): ReactNode => {
    const [persona, setPersona] = useState<PersonaFisicaData>(emptyPersona);
    const [isLoading, setIsLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);

    const dataNascita = useMemo(() => {
        const value = persona.dataNascita?.trim();
        if (!value) {
            return '';
        }

        const normalizedValue = value.includes('T') ? value.split('T')[0] : value;
        const match = normalizedValue.match(/^(\d{4})-(\d{2})-(\d{2})$/);
        if (match) {
            return `${match[3]}/${match[2]}/${match[1]}`;
        }

        return value;
    }, [persona.dataNascita]);

    const luogoNascita = useMemo(() => {
        const descrizione = persona.comuneNascitaDescrizione?.trim();
        return descrizione || '';
    }, [persona.comuneNascitaDescrizione]);

    const generaAnagrafica = async () => {
        setIsLoading(true);
        setError(null);

        try {
            const response = await fetch('/persona-fisica/random', {
                headers: {
                    Accept: 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error('Errore durante la generazione dell’anagrafica');
            }

            const data = await response.json() as PersonaFisicaData;
            setPersona(data);
        } catch {
            setError('Impossibile generare l’anagrafica. Riprova tra poco.');
        } finally {
            setIsLoading(false);
        }
    };

    return (
        <Page browserTitle={'Codice fiscale'} className={'py-5'}>
            <div className="container">
                <div className="d-flex justify-content-between align-items-center mb-4">
                    <h1 className="h2 mb-0">Codice fiscale</h1>
                    <button className="btn btn-primary" type="button" onClick={generaAnagrafica} disabled={isLoading}>
                        {isLoading ? 'Generazione in corso...' : 'Genera anagrafica random'}
                    </button>
                </div>

                {error && (
                    <div className="alert alert-danger" role="alert">
                        {error}
                    </div>
                )}

                <div className="card">
                    <div className="card-body">
                        <div className="row g-3">
                            <div className="col-12 col-md-6">
                                <label className="form-label">Codice fiscale</label>
                                <input className="form-control" value={persona.codiceFiscale} readOnly/>
                            </div>
                            <div className="col-12 col-md-6">
                                <label className="form-label">Sesso</label>
                                <input className="form-control" value={persona.sesso} readOnly/>
                            </div>
                            <div className="col-12 col-md-6">
                                <label className="form-label">Cognome</label>
                                <input className="form-control" value={persona.cognome} readOnly/>
                            </div>
                            <div className="col-12 col-md-6">
                                <label className="form-label">Nome</label>
                                <input className="form-control" value={persona.nome} readOnly/>
                            </div>
                            <div className="col-12 col-md-6">
                                <label className="form-label">Data nascita</label>
                                <input className="form-control" value={dataNascita} readOnly/>
                            </div>
                            <div className="col-12 col-md-6">
                                <label className="form-label">Luogo nascita</label>
                                <input className="form-control" value={luogoNascita} readOnly/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Page>
    );
}

export default CodiceFiscale;
