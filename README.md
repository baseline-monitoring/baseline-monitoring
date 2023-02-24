
# Baseline Monitoring

Imagine you have a grown PHP application and you are using PHPStan or Psalm.
A lot of projects are using baseline files to ignore errors. 
Especially when you change levels or update the tool you are faced with lots of
new errors. 
As time goes by the baseline is growing and you miss the point where you can easily
solve all issues in the baseline file. 

This project is the solution for you! You can monitor multiple baselines over time
and can see trends. You have a measured information and you can rely on numbers
instead of hearing "everything is going to be better". 

When you are working in a company you can also use the baseline monitoring chart
for intensive discussions with your managers. 

And on top you can set goals for the project, I have implemented a little gamification.
Set goals for your team like "if we reach 10000 errors, we'll have a teamevent". 
Feel free to set goals and awake the intrinsic motivation of your team members. 

Clone the project and deploy it to one of your servers. You can run it via docker.
There is no external dependency. The main goal here is that you can work with it independently.
## Contributing

Contributions are always welcome!

See `CONTRIBUTING.md` for ways to get started. Please also have a look on the project's `code of conduct`.


## Feedback

If you have any feedback, please reach out to us at dario.tilgner@gmail.com. 
Please feel free to email me. No matter if it is good feedback or criticism. 


## Tech Stack

PHP >= 8.1

MySQL 8

Symfony 6.2

Docker
## Run Locally

Clone the project

```bash
  git clone git@github.com:baseline-monitoring/baseline-monitoring.git
```

Go to the project directory

```bash
  cd baseline-monitoring
```

Start the docker containers
```bash
docker-compose up -d --build
```

Access the application container
```bash
docker-compose exec php /bin/bash
```

Inside your container install the dependencies
```bash
  composer install
  npm install
```

Access the Tool via: http://localhost:8080/
## Authors

- [@dariotilgner](https://www.github.com/dariotilgner)

