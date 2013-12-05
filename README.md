# Nette Generator

Rozšíření pro generování presenterů, controllerů, entit atd. Generování probíhá pomocí konzole je nezbytné tedy využívat Kdyby/Console, podobně jako při generování schéma přes Doctrine (pokud nevyužíváte Kdyby/Doctrine, pak je pro Vás toto rozšíření zbytečné.

Rozšíření přidává nové příkazy, které se běžně volají přes konzoli nad **index.php**, konkrétní informace o generování viz níže.


## Generování presenteru

```
php index.php generate:presenter
```

**What's the name of presenter?**
Název presenteru, například *Homepage* pro generování *HomepagePresenter*

**What's the module name?**
Název modulu, například *admin* pro **App\AdminModule*

**Is secured?**
Určuje zda bude rozšiřovat *BasePresenter* nebo *SecuredPresenter*

**Do you add autowired property?**
Přidat autowired property presenteru (předpokládá využívání Kdyby/Autowired), pokud zadáte například: *manager Kdyby\Doctrine\EntityDao*, pak vygeneruje property názvem *$user* kde se autowiruje služba *Kdyby\Doctrine\EntityDao*.

Dále je zde speciální možnost s využitím znaku vykřičníku na začátku názvu property, pro autowiring DAO objektů z Kdyby\Doctrine například při zápisu: *!user App\User\User*, což vygeneruje property *$user* pro autowirování služby DAO pro entitu *App\User\User*.

**Do you add action?**
Přidat akci do presenteru, například *default* vygeneruje metodu *actionDefault*
