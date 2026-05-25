import {usePage} from "@inertiajs/react";
import {ChangeEvent, FC, FocusEvent, KeyboardEvent, ReactNode, useMemo, useState} from "react";
import Page from "../components/Page/Page.tsx";
import {PersonaFisicaData} from "../../../types/generated";
import {SharedPageProps} from "../../page.types.ts";
import {useRoutes} from "../../../hooks/useRoutes.ts";
import useInertia from "../../../hooks/useInertia.ts";

const emptyPersona: PersonaFisicaData = {
    codiceFiscale: '',
    cognome: '',
    nome: '',
    sesso: '',
    dataNascita: '',
    comuneNascitaCodice: '',
    comuneNascitaDescrizione: '',
};

type CodiceFiscalePageProps = SharedPageProps & {
    persona?: PersonaFisicaData | null;
    luoghiNascitaOptions?: string[];
};

const maxLuoghiNascitaSuggestions = 12;

const formatDateForDisplay = (value?: string | null): string => {
    const trimmedValue = value?.trim();
    if (!trimmedValue) {
        return '';
    }

    const normalizedValue = trimmedValue.includes('T') ? trimmedValue.split('T')[0] : trimmedValue;
    const match = normalizedValue.match(/^(\d{4})-(\d{2})-(\d{2})$/);

    if (match) {
        return `${match[3]}/${match[2]}/${match[1]}`;
    }

    return '';
};

const normalizeDateForInput = (value?: string | null): string => {
    return formatDateForDisplay(value);
};

const formatDateInput = (value: string): string => {
    const digits = value.replace(/\D/g, '').slice(0, 8);
    const day = digits.slice(0, 2);
    const month = digits.slice(2, 4);
    const year = digits.slice(4, 8);

    if (digits.length <= 2) {
        return day;
    }

    if (digits.length <= 4) {
        return `${day}/${month}`;
    }

    return `${day}/${month}/${year}`;
};

type PersonaFormState = {
    cognome: string;
    nome: string;
    dataNascita: string;
    sesso: string;
    comuneNascitaDescrizione: string;
};

const toFormState = (persona: PersonaFisicaData): PersonaFormState => ({
    cognome: (persona.cognome ?? '').toUpperCase(),
    nome: (persona.nome ?? '').toUpperCase(),
    dataNascita: normalizeDateForInput(persona.dataNascita),
    sesso: persona.sesso ?? '',
    comuneNascitaDescrizione: persona.comuneNascitaDescrizione?.trim() ?? '',
});

const CodiceFiscale: FC = (): ReactNode => {
    const {app} = useRoutes();
    const {inertiaRouter} = useInertia();
    const {persona: generatedPersona, luoghiNascitaOptions = []} = usePage<CodiceFiscalePageProps>().props;
    const [isLoading, setIsLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);
    const persona = generatedPersona ?? emptyPersona;
    const [codiceFiscaleValue, setCodiceFiscaleValue] = useState(persona.codiceFiscale ?? '');
    const [isCodiceFiscaleCleared, setIsCodiceFiscaleCleared] = useState(false);
    const [formData, setFormData] = useState<PersonaFormState>(() => toFormState(persona));
    const [isLuogoNascitaOpen, setIsLuogoNascitaOpen] = useState(false);
    const [highlightedLuogoNascitaIndex, setHighlightedLuogoNascitaIndex] = useState(0);

    const filteredLuoghiNascita = useMemo(() => {
        const searchValue = formData.comuneNascitaDescrizione.trim().toLocaleUpperCase('it-IT');

        if (!searchValue) {
            return luoghiNascitaOptions.slice(0, maxLuoghiNascitaSuggestions);
        }

        const startsWithMatches: string[] = [];
        const containsMatches: string[] = [];

        for (const luogo of luoghiNascitaOptions) {
            const normalizedLuogo = luogo.toLocaleUpperCase('it-IT');

            if (normalizedLuogo.startsWith(searchValue)) {
                startsWithMatches.push(luogo);
            } else if (normalizedLuogo.includes(searchValue)) {
                containsMatches.push(luogo);
            }

            if (startsWithMatches.length + containsMatches.length >= maxLuoghiNascitaSuggestions) {
                break;
            }
        }

        return [...startsWithMatches, ...containsMatches].slice(0, maxLuoghiNascitaSuggestions);
    }, [formData.comuneNascitaDescrizione, luoghiNascitaOptions]);

    const clearCodiceFiscale = () => {
        setIsCodiceFiscaleCleared(true);
    };

    const handleTextFieldChange = (event: ChangeEvent<HTMLInputElement>) => {
        const {name, value} = event.target;
        const normalizedValue = name === 'dataNascita'
            ? formatDateInput(value)
            : (name === 'cognome' || name === 'nome')
                ? value.toUpperCase()
                : value;

        setFormData((prevState) => ({
            ...prevState,
            [name]: normalizedValue,
        }));
        clearCodiceFiscale();
    };

    const handleLuogoNascitaChange = (event: ChangeEvent<HTMLInputElement>) => {
        handleTextFieldChange(event);
        setIsLuogoNascitaOpen(true);
        setHighlightedLuogoNascitaIndex(0);
    };

    const selectLuogoNascita = (luogo: string) => {
        setFormData((prevState) => ({
            ...prevState,
            comuneNascitaDescrizione: luogo,
        }));
        setIsLuogoNascitaOpen(false);
        setHighlightedLuogoNascitaIndex(0);
        clearCodiceFiscale();
    };

    const handleLuogoNascitaFocus = () => {
        setIsLuogoNascitaOpen(true);
    };

    const handleLuogoNascitaBlur = (event: FocusEvent<HTMLDivElement>) => {
        if (!event.currentTarget.contains(event.relatedTarget)) {
            setIsLuogoNascitaOpen(false);
            setHighlightedLuogoNascitaIndex(0);
        }
    };

    const handleLuogoNascitaKeyDown = (event: KeyboardEvent<HTMLInputElement>) => {
        if (!isLuogoNascitaOpen && (event.key === 'ArrowDown' || event.key === 'ArrowUp')) {
            setIsLuogoNascitaOpen(true);
            return;
        }

        if (!filteredLuoghiNascita.length) {
            return;
        }

        if (event.key === 'ArrowDown') {
            event.preventDefault();
            setHighlightedLuogoNascitaIndex((currentIndex) => (
                currentIndex + 1 >= filteredLuoghiNascita.length ? 0 : currentIndex + 1
            ));
        }

        if (event.key === 'ArrowUp') {
            event.preventDefault();
            setHighlightedLuogoNascitaIndex((currentIndex) => (
                currentIndex - 1 < 0 ? filteredLuoghiNascita.length - 1 : currentIndex - 1
            ));
        }

        if (event.key === 'Enter' && isLuogoNascitaOpen) {
            event.preventDefault();
            selectLuogoNascita(filteredLuoghiNascita[highlightedLuogoNascitaIndex]);
        }

        if (event.key === 'Escape') {
            setIsLuogoNascitaOpen(false);
            setHighlightedLuogoNascitaIndex(0);
        }
    };

    const generaAnagrafica = () => {
        setIsLoading(true);
        setError(null);

        inertiaRouter.post(app.codiceFiscaleGenera(), {}, {
            only: ['persona'],
            replace: true,
            onSuccess: (page) => {
                const props = page.props;
                const nextPersona = props.persona as PersonaFisicaData | undefined;

                if (!props.persona) {
                    setError('Impossibile generare l’anagrafica. Riprova tra poco.');
                    return;
                }

                if (nextPersona) {
                    setFormData(toFormState(nextPersona));
                }
                setCodiceFiscaleValue(nextPersona?.codiceFiscale ?? '');
                setIsCodiceFiscaleCleared(false);
            },
            onFinish: () => setIsLoading(false),
        });
    };

    const calcolaCodiceFiscale = () => {
        setIsLoading(true);
        setError(null);

        inertiaRouter.post(app.codiceFiscaleCalcola(), formData, {
            replace: true,
            onSuccess: (page) => {
                const props = page.props;
                const nextPersona = props.persona as PersonaFisicaData | undefined;

                if (!nextPersona) {
                    setError('Compila tutti i dati per calcolare il codice fiscale.');
                    return;
                }

                setFormData(toFormState(nextPersona));
                setCodiceFiscaleValue(nextPersona.codiceFiscale ?? '');
                setIsCodiceFiscaleCleared(false);
            },
            onFinish: () => setIsLoading(false),
        });
    };

    return (
        <Page browserTitle={'Codice fiscale'} className={'py-5'}>
            <div className="container">
                <div className="mb-4">
                    <h1 className="h2 mb-0">Codice fiscale</h1>
                </div>

                {error && (
                    <div className="alert alert-danger" role="alert">
                        {error}
                    </div>
                )}

                <div className="card border-primary mb-4">
                    <div className="card-body bg-light">
                        <label className="form-label fw-semibold text-primary">Codice fiscale</label>
                        <input className="form-control form-control-lg fw-semibold text-uppercase text-primary"
                               value={isCodiceFiscaleCleared ? '' : (codiceFiscaleValue || persona.codiceFiscale)}
                               readOnly/>
                    </div>
                </div>

                <div className="card">
                    <div className="card-body">
                        <h2 className="h5 mb-3">Dati anagrafici</h2>
                        <div
                            className="row g-3"
                            key={`${persona.cognome}|${persona.nome}|${persona.dataNascita}|${persona.sesso}|${persona.comuneNascitaDescrizione}`}
                        >
                            <div className="col-12 col-md-6">
                                <label className="form-label">Cognome</label>
                                <input className="form-control" name="cognome" value={formData.cognome}
                                       onChange={handleTextFieldChange}/>
                            </div>
                            <div className="col-12 col-md-6">
                                <label className="form-label">Nome</label>
                                <input className="form-control" name="nome" value={formData.nome}
                                       onChange={handleTextFieldChange}/>
                            </div>
                            <div className="col-12 col-md-6">
                                <label className="form-label">Data nascita</label>
                                <input className="form-control" type="text" placeholder="gg/mm/aaaa"
                                       name="dataNascita" value={formData.dataNascita}
                                       onChange={handleTextFieldChange}/>
                            </div>
                            <div className="col-12 col-md-6">
                                <label className="form-label">Sesso</label>
                                <div className="d-flex gap-4 mt-1">
                                    <div className="form-check">
                                        <input
                                            className="form-check-input"
                                            type="radio"
                                            name="sesso"
                                            id="sesso-m"
                                            value="M"
                                            checked={formData.sesso === 'M'}
                                            onChange={handleTextFieldChange}
                                        />
                                        <label className="form-check-label" htmlFor="sesso-m">M</label>
                                    </div>
                                    <div className="form-check">
                                        <input
                                            className="form-check-input"
                                            type="radio"
                                            name="sesso"
                                            id="sesso-f"
                                            value="F"
                                            checked={formData.sesso === 'F'}
                                            onChange={handleTextFieldChange}
                                        />
                                        <label className="form-check-label" htmlFor="sesso-f">F</label>
                                    </div>
                                </div>
                            </div>
                            <div className="col-12 col-md-6">
                                <label className="form-label">Luogo nascita</label>
                                <div
                                    className="position-relative"
                                    onBlur={handleLuogoNascitaBlur}
                                >
                                    <input
                                        className="form-control pe-5"
                                        name="comuneNascitaDescrizione"
                                        value={formData.comuneNascitaDescrizione}
                                        autoComplete="off"
                                        role="combobox"
                                        aria-expanded={isLuogoNascitaOpen}
                                        aria-controls="luoghi-nascita-list"
                                        aria-autocomplete="list"
                                        onChange={handleLuogoNascitaChange}
                                        onFocus={handleLuogoNascitaFocus}
                                        onKeyDown={handleLuogoNascitaKeyDown}
                                    />
                                    <span
                                        className="position-absolute top-50 end-0 translate-middle-y me-3 text-muted"
                                        aria-hidden="true"
                                    >
                                        🔍
                                    </span>
                                    {isLuogoNascitaOpen && filteredLuoghiNascita.length > 0 && (
                                        <div
                                            id="luoghi-nascita-list"
                                            className="list-group luogo-nascita-suggestions shadow-sm"
                                            role="listbox"
                                        >
                                            {filteredLuoghiNascita.map((luogo, index) => (
                                                <button
                                                    key={luogo}
                                                    type="button"
                                                    className={`list-group-item list-group-item-action luogo-nascita-suggestion ${
                                                        index === highlightedLuogoNascitaIndex ? 'active' : ''
                                                    }`}
                                                    role="option"
                                                    aria-selected={index === highlightedLuogoNascitaIndex}
                                                    onMouseDown={(event) => event.preventDefault()}
                                                    onMouseEnter={() => setHighlightedLuogoNascitaIndex(index)}
                                                    onClick={() => selectLuogoNascita(luogo)}
                                                >
                                                    {luogo}
                                                </button>
                                            ))}
                                        </div>
                                    )}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="mt-4 d-flex gap-2">
                    <button className="btn btn-primary" type="button" onClick={generaAnagrafica} disabled={isLoading}>
                        Genera anagrafica random
                    </button>
                    <button className="btn btn-outline-primary" type="button" onClick={calcolaCodiceFiscale}
                            disabled={isLoading}>
                        Calcola da dati inseriti
                    </button>
                </div>
            </div>
        </Page>
    );
}

export default CodiceFiscale;
