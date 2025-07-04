## GITHUB ACTIONS

Há dois workflows configurados para a aplicação, sempre que um Pull Request é aberto para a branch `main` (default), elas são disparadas e só podem ser mergeados caso esteja com o status de Sucesso.

### Estilo de Código
A Action que valida o estilo de código segue as regras definidas no arquivo [php-cs-fixer.php](./../.php-cs-fixer.php), que são em sua maioria baseadas em alguns principios básicos:
- PSR-12
- Clean Code
- Object Calisthenics 
- Outras

### Testes Automatizados
Essa Action valida se os testes automatizados estão passando corretamente, tantos os testes unitários, como os testes funcionais 
