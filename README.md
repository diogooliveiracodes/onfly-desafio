# Guia de Instalação
Siga os passos abaixo para configurar o projeto no seu ambiente local.
Verifique se as portas configuradas para este projeto estão liberadas em sua máquina:
- 3307 -> mysql
- 8000 -> backend
- 3000 -> frontend

## 1. Clone o repositório

```
git clone https://github.com/diogooliveiracodes/onfly-desafio.git
```

## 2. Abra a pasta do projeto

```
cd cd onfly-desafio
```

## 3. Provisionar o sistema
Verifique se está na pasta raiz do projeto "onfly-desafio" e rode o seguinte comando:

```
docker-compose run migrate
```

Este comando irá aplicar as migrations necessárias, criar o arquivo .env com as configurações padrão e gerar a chave APP_KEY para o ambiente do seu projeto.

## 4. Iniciar o Backend e o Frontend

Em seguida, inicie os serviços do backend e frontend utilizando o Docker:

```
docker-compose up backend frontend -d
```
- Nota: Os containers podem levar algum tempo para serem completamente inicializados. Durante esse processo, você pode verificar a conslusão ao acessar o frontend em localhost:3000 e o backend em localhost:8000. <br>


## 5. Entre no container do projeto back-end
```
docker exec -it onfly-desafio-backend bash
```

## 6. Rodar os Testes

Para garantir que tudo esteja funcionando corretamente, rode os testes dentro do container do projeto back-end:

```
php artisan test --env=testing
```