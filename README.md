
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

Add the following lines to your `/etc/hosts` file:
```bash
127.0.0.1 local.baseline-monitoring.de
127.0.0.1 localdb.baseline-monitoring.de
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
docker-compose exec app /bin/bash
```

Inside your container install the dependencies
```bash
composer install
npm install
```

To make sure the application will work, we have to add the database and run migrations:
```bash
bin/console doctrine:database:create --if-not-exists
bin/console doctrine:migrations:migrate
```

Access the tool in your browser via: http://local.baseline-monitoring.de/

Login for admin user:

**Username**: `admin`

**Password**: `changeme`

I'd recommend to change the password => http://local.baseline-monitoring.de/profile/change-password/ 

## First steps

### 1. Create a remote server configuration
This configuration is only accessible if you are logged in as an admin user. 

http://local.baseline-monitoring.de/admin/remote-server-configuration/

The remote server is the hoster of your repository. It can be github or something like a hosted bitbucket.
All information you need to add is the name, host and a private key. 

Why do this application uses a private key? If you frequently clone repositories via https 
you can run into http request limitations. Therefore, this application is doing everything via ssh.
**Important**: In your software (github, bitbucket and so on) use a separate user with an extra ssh key. DON'T ever reuse ssh keys which you are using for other things (application, infrastructure, ..)

### 2. Add a baseline configuration
This configuration is only accessible if you are logged in as an admin user.

http://local.baseline-monitoring.de/admin/baseline-configuration/

Add a baseline configuration here. Most important information here is the repository url and 
paths to the phpstan/psalm configuration file and the baseline. 
If you have multiple baselines you have to add a second baseline configuration at the moment.
Also please specify the branch which should be used for analysing the baseline. 
Mostly it is something like `main`, `develop`, `integration` or something like that. 

### 3. Get baseline data
There are two ways of get things work:
1. Every hours at minute 43 a cronjob will analyze all configured baselines
2. Login to the docker container with `docker-compose exec app /bin/bash` 
and execute `bin/console app:run` to analyze all configured baselines

Baselines will show up on the index page: http://local.baseline-monitoring.de/

You can click on the "show" action link at every baseline to see the chart with errors
(Hopefully there is a trend to zero errors :) ).
In the navigation on the left side you can also click on "Error List" 
to see all errors of the last analyzed commit.

### 4. Spice things a bit up (optional!)
If you need some extra motivation you can edit your baseline configuration and add "goals".
This is a gamification with the aim of waken up the intrinsic motivation. For example, you can 
have a team event or lunch (or something else - be creative) when having X errors. Assume you have 5000 errors and the goal is achieved
when your project has only 4000 errors. Define here whatever you want. 

This feature is not a must-have, but I'd recommend to try it out. Sometimes it is surprising
how much gamification can influence the intrinsic motivation. 

### 5. Add guidelines for developing your project
Use the chart to see the progress. 
In my team we have the guideline to not add something to the baseline. If you need to add something
to the baseline you have to have a very good excuse for that. 

Use step 4 to motivate people. The main goal is to have zero errors. 
If you have achieved this, you can uninstall this application and be happy 
that your application world is a better place now.

## Authors

- [@dariotilgner](https://www.github.com/dariotilgner)

