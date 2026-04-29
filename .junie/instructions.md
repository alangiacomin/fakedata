# Regole Generali di Generazione Codice

- Tutto il codice generato deve essere scritto con nomi, commenti e documentazione in lingua italiana.
- I commenti nel codice devono essere sempre in italiano.
- I nomi di classi, metodi e variabili devono usare terminologia italiana coerente con il dominio applicativo.
- Eccezioni: nomi di librerie, namespace di framework e API esterne devono rimanere nella loro forma originale.

# Ambito di Queste Istruzioni

Queste regole si applicano **solo al backend**. L'obiettivo è mantenere la **Clean Architecture** con CQRS, rendendo
esplicito dove aggiungere ogni componente (Controller, Service, DTO, Entity, Model, Repository, Mapper, ecc.).

# Struttura dell'Applicazione

L'applicazione è organizzata in aree funzionali dentro `app/Areas`.

## Aree Principali

- `app/Areas/Main`: funzionalità utente finale.
- `app/Areas/Admin`: funzionalità backoffice/amministrazione.

## Struttura Modulo (Backend)

Percorso base:

- `app/Areas/{Area}/{Module}/`

Struttura minima attesa:

```text
app/Areas/{Area}/{Module}/
  Domain/
    Entities/
    Repositories/
    Events/
  Application/
    Commands/
    Services/
    Contracts/
    Data/
  Infrastructure/
    Repositories/
    Mappers/
    DataSources/
      Dto/
  Presentation/
    Http/
      Controllers/
      Requests/
```

Note:

- Le cartelle sono create **solo se necessarie** al modulo.
- Se il modulo usa CQRS per mutazioni, privilegia `Application/Commands`.
- `Application/Services` è ammesso per orchestrazioni applicative riusabili.

## Esempio di riferimento: `TodoList`

Usa sempre gli esempi con naming coerente al modulo `TodoList` (es. `TodoItem`, `CreaTodoCommand`, `TodoController`).

### 1. Domain (cuore del business)

Contiene regole di business pure e indipendenti dalla tecnologia.

- `Entities`: entità di dominio (es. `TodoItem`) con comportamento e invarianti.
- `Repositories`: interfacce (es. `ITodoRepository`) richieste dal dominio.
- `Events`: eventi di dominio (es. `TodoCompletato`).

Vincoli obbligatori Domain:

- NON usare Laravel (`facade`, helper, container, request, response).
- NON usare Eloquent (`Model`, `Builder`, relazioni, query builder).
- NON dipendere da `Infrastructure` o `Presentation`.

Quando aggiungere nuovi pezzi nel Domain:

- Nuova regola/invariante di business -> `Domain/Entities` (o Value Object se necessario).
- Nuova esigenza di persistenza lato business -> interfaccia in `Domain/Repositories`.
- Nuovo evento di business significativo -> `Domain/Events`.

### 2. Application (casi d'uso)

Coordina il dominio e orchestra i flussi applicativi.

- `Commands`: operazioni CQRS che **modificano stato** (es. `CreaTodoCommand`).
- `Services`: logica applicativa riusabile/orchestrazione tra più componenti applicativi.
- `Contracts`: interfacce applicative (es. service/factory interface).
- `Data`: DTO applicativi per input/output dei casi d'uso.

Regole per i DTO in `Application/Data`:

- Devono estendere `Spatie\LaravelData\Data` quando allineato al modulo.
- Se usati per passaggio dati al frontend via Inertia, includere `#[TypeScript]`.

Regole Commands:

- Ricevono dati dal controller (direttamente o via DTO).
- Implementano il caso d'uso senza accesso diretto a Eloquent.
- Usano interfacce del Domain (repository o altri contratti).

Regole dipendenze framework in `Application`:

- È consentito usare utility Laravel quando utili al caso d'uso (es. `Collection`, `Carbon`, helper/facade non legati a
  persistenza Eloquent).
- È vietato referenziare direttamente model Eloquent (`app/Models`) nel layer `Application`.
- Le dipendenze da model Eloquent restano delegate a `Infrastructure`.

Quando usare `Service` vs `Command`:

- Mutazione atomica di stato -> `Command`.
- Orchestrazione riusabile, composizione logica applicativa o logica trasversale al caso d'uso -> `Service`.

### 3. Infrastructure (dettagli tecnici)

Implementa adapter concreti e accesso ai datasource.

- `Repositories`: implementazioni concrete delle interfacce Domain.
- `Mappers`: conversione tra Eloquent Model e Domain Entity.
- `DataSources/Dto`: DTO specifici per integrazioni esterne (API, file, provider terzi), non esposti al Domain.

Regole Infrastructure:

- È l'unico layer che può usare `app/Models` e Eloquent.
- Non contiene regole di business di dominio.
- Traduce sempre i dati tecnici in oggetti utili ai layer interni (entity/DTO applicativi).

Quando aggiungere nuovi pezzi in Infrastructure:

- Nuovo accesso DB -> repository concreto in `Infrastructure/Repositories` + mapper.
- Nuova integrazione esterna -> adapter in `Infrastructure/DataSources` e DTO tecnici in
  `Infrastructure/DataSources/Dto`.
- Nuova conversione model/entity -> mapper in `Infrastructure/Mappers`.

### 4. Presentation (HTTP)

Gestisce protocollo HTTP, validazione e delega ai casi d'uso.

- `Http/Controllers`: endpoint/controller sottili.
- `Http/Requests`: validazione input HTTP.

Responsabilità del Controller (thin controller):

- ricevere la Request
- validare input tramite Request class
- costruire DTO applicativi se necessari
- invocare Command/Service del layer Application
- restituire risposta (redirect/render Inertia)

Il Controller NON deve:

- contenere logica di business
- usare Eloquent direttamente
- dipendere da classi `Infrastructure`

## Posizionamento Componenti (mappa rapida)

- Endpoint/chiamate HTTP -> `Presentation/Http/Controllers`
- Validazione input HTTP -> `Presentation/Http/Requests`
- Caso d'uso di scrittura (CQRS) -> `Application/Commands`
- Logica applicativa riusabile -> `Application/Services`
- DTO applicativi (input/output use case) -> `Application/Data`
- DTO tecnici di datasource esterni -> `Infrastructure/DataSources/Dto`
- Entità di dominio -> `Domain/Entities`
- Contratti repository di dominio -> `Domain/Repositories`
- Implementazioni repository concrete -> `Infrastructure/Repositories`
- Mapper tra model e entity -> `Infrastructure/Mappers`
- Model database (Eloquent) -> `app/Models` (uso solo Infrastructure)

## Modelli Eloquent

I model Eloquent Laravel risiedono in `app/Models`.

Vincoli:

- Possono essere usati **solo** in `Infrastructure`.
- NON usare model Eloquent in `Domain`, `Application`, `Presentation`.
- Conversione verso il dominio tramite mapper dedicati.

## Policy di Autorizzazione

Le policy Laravel devono risiedere in `app/Models/Policies`.

Regole obbligatorie:

- Namespace: `App\Models\Policies`.
- Non creare policy in `app/Policies`.

## Flusso Standard (backend)

`HTTP Request`
-> `Request Validation (Presentation)`
-> `Controller (Presentation)`
-> `Command/Service (Application)`
-> `Repository Interface (Domain)`
-> `Repository concreto (Infrastructure)`
-> `Mapper`
-> `Entity/DTO`
-> `Controller Response (redirect/render Inertia)`

## Regole di Dipendenza tra Layer

Direzioni consentite:

- `Presentation` -> `Application` -> `Domain`
- `Infrastructure` -> `Domain`

Direzioni vietate:

- `Domain` -> qualunque layer esterno o Laravel
- `Application` -> implementazioni `Infrastructure`
- `Presentation` -> `Infrastructure` diretto

## Namespace

I namespace seguono il filesystem.

Esempio modulo `TodoList` nell'area `Main`:

- `App\Areas\Main\TodoList\Domain\Entities`
- `App\Areas\Main\TodoList\Application\Commands`
- `App\Areas\Main\TodoList\Application\Services`
- `App\Areas\Main\TodoList\Infrastructure\Repositories`
- `App\Areas\Main\TodoList\Presentation\Http\Controllers`

## Convenzioni di Naming

- Entity: nome singolare dominio (es. `TodoItem`)
- Command: verbo + entità (es. `CreaTodoCommand`)
- Service: nome funzione applicativa + `Service` (es. `GestioneTodoService`)
- DTO applicativo: entità/contesto + `Data` (es. `TodoItemData`)
- Repository interface: prefisso `I` (es. `ITodoRepository`)
- Repository implementation: entità + `Repository` (es. `TodoRepository`)
- Mapper: entità + `Mapper` (es. `TodoItemMapper`)
- Controller: contesto + `Controller` (es. `TodoController`)
- Request: azione + `Request` (es. `CreaTodoRequest`)

## Checklist quando aggiungi una feature backend

1. Determina `Area` e `Module`.
2. Inserisci ogni componente nel layer corretto (usa la mappa rapida sopra).
3. Mantieni i vincoli di dipendenza della Clean Architecture.
4. Se muta stato, implementa il caso d'uso tramite `Command`.
5. Se integri un datasource esterno, isola adapter e DTO tecnici in `Infrastructure`.
6. Evita duplicazioni: estendi codice esistente quando coerente.

## Regola di Enforcement

Quando generi codice devi rispettare rigorosamente questa architettura.
Se una richiesta viola queste regole, correggi automaticamente l'implementazione mantenendo i vincoli definiti in questo
documento.
