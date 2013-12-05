# Nette Generator

Rozšíření pro generování presenterů, controllerů, entit atd. Generování probíhá pomocí konzole je nezbytné tedy využívat Kdyby/Console, podobně jako při generování schéma přes Doctrine (pokud nevyužíváte Kdyby/Doctrine, pak je pro Vás toto rozšíření zbytečné.

Rozšíření přidává nové příkazy, které se běžně volají přes konzoli nad **index.php**, konkrétní informace o generování viz níže.



## Generování presenteru

```
php index.php generate:presenter
```

**What's the name of presenter?**
Název presenteru, například `Homepage` pro generování *HomepagePresenter*

**What's the module name?**
Název modulu, například `admin` pro *App\AdminModule*

**Is secured?**
Určuje zda bude rozšiřovat *BasePresenter* nebo *SecuredPresenter*

**Do you add autowired property?**
Přidat autowired property presenteru (předpokládá využívání Kdyby/Autowired), pokud zadáte například: `manager Kdyby\Doctrine\EntityDao`, pak vygeneruje property názvem *$user* kde se autowiruje služba *Kdyby\Doctrine\EntityDao*.

Dále je zde speciální možnost s využitím znaku vykřičníku na začátku názvu property, pro autowiring DAO objektů z Kdyby\Doctrine například při zápisu: `!user App\User\User`, což vygeneruje property *$user* pro autowirování služby DAO pro entitu *App\User\User*.

**Do you add action?**
Přidat akci do presenteru, například `default` vygeneruje metodu *actionDefault*



## Generování entit


## Generování query objektů

```
php index.php generate:query
```

**What's the namespace of class?**
Namespace query objektu, například při zadání `user` vygeneruje namespace *App\User*

**What's the name of query?**
Název query, například pro `test` vytvoří třídu `TestQuery`

**Do you add construct argument? Type name space and class type:**
Přidání povinného parametru v konstruktoru, například při zadání `foo` vygeneruje property *foo*, a zároveň constructor injection pri proměnnou. Při zadání `user \App\User\User` vygeneruje property *user* s typehintem pro *\App\User\User*

**Do you add setter argument? Type name space and class type:**
Stejné jako při předchozím contructor injection, ale pro setter injection - používá se pro volitelné argumenty.