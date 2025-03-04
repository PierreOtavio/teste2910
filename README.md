Passo a passo:
Passo 1: Baixar Deve baixar o repositório no seu VS Code, pode ser feito da seguinte forma(selecione a branch "funcional"):
1 - download do arquivo zip (clique em "", um botão verde, e selecione Download zip);
2 - "deszipar" a pasta;
3 - abrir a pasta no VS Code;

Passo 2: Xampp
Deve iniciar a conexão MYSQL pelo xampp (alguns casos)

Passo 3: Banco de Dados
Deve criar o banco de dados em seu computador por meio de duas formas:
1 - MYSQL workbench
2 - comandos cmd :
2.1 - mysql -u [usuário] -p (o usuário usamos root e o '-p' é somente se precisar de senha para acessar a conexão MYSQL)
2.2 - CREATE DATABASE [nome] (comando para criar o database);

Passo 4: Extensões
Deve executar os seguintes comandos:
1 - composer install
2 - cp .env.example .env
3 - php artisan key:generate
Configurar o arquivo .env:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=alfa1 (trocar o nome do banco de dados de acordo com o que foi criado no 'cmd' ou no 'workbench')
DB_USERNAME=root
DB_PASSWORD= (colocar a senha caso seu acesso ao mysql necessite de senha)
4 - php artisan migrate
5 - composer require simplesoftwareio/simple-qrcode
6 - composer require dompdf/dompdf
7 - composer require setasign/fpdi-fpdf
8 - composer require phpoffice/phpspreadsheet
9 - php artisan serve (irá abrir a página web)

Passo 5: Fazer o Register
No canto superior direito irá aparecer duas opções: login e register, por ser a primeira vez, faça o register e pronto, já tem o acesso às views.
