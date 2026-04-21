import {FC, ReactNode} from "react";

type DailyMottoProps = {
    motto: string;
}

const DailyMotto: FC<DailyMottoProps> = ({motto}: DailyMottoProps): ReactNode => {
    return (
        <section className="bg-body-tertiary border-top py-3" aria-label="Motto del giorno">
            <div className="container text-center">
                <p className="mb-0 text-body-secondary">
                    <span className="fw-semibold text-body">Pensiero senza fronzoli:</span> {motto}
                </p>
            </div>
        </section>
    );
}

export default DailyMotto;
