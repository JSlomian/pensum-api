# Pensum API
This is a symfony 7.2 API using api platform for engineering thesis defence.
This is part one of two consisting of backend service.

## Running in development

Download this repository and make sure you have ddev and docker installed.

1. Start project
```bash
ddev start
```

2. Run migrations
```bash
ddev php bin/console doctrine:migrations:migrate
```

3. Fill database with starting data
```bash
ddev php bin/console doctrine:fixtures:load
```
