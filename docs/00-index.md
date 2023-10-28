# Internal Docs

### Models que podem ser acessadas apenas via escopo de **tenant**

Se tiver uma model que só pode ser acessada dentro do escopo de tenant, basta adicionar o método estático `onlyTenantScope` com visibilidade **public**.

Pode ser útil em cenários como quando a tabela não existe fora do contexto/schema ou quando a regra de negócio exige isso.

Exemplo:
```php
public static function onlyTenantScope(): bool
{
    // Legenda:
    // true:  Isso diz que essa model (a resource identifica isso) só permite ser acessada se tiver tenant iniciado
    // false: Pode ser acessada com ou sem contexto de tenant
    return true;
}
```
-----

### Models que podem ser acessadas apenas via escopo **global**
onlyGlobalScope

Se tiver uma model que só pode ser acessada FORA do escopo de tenant, basta adicionar o
método estático `onlyGlobalScope` com visibilidade **public**.

Muito útil por exemplo em uma resource de administração global e administrativa.

Exemplo:
```php
public static function onlyGlobalScope(): bool
{
    // Legenda:
    // true:  Isso diz que essa model (a resource identifica isso) só permite ser acessada se tiver FORA do contexto de tenant
    // false: Pode ser acessada com ou sem contexto de tenant
    return true;
}
```
