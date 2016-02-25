# Contributing to Anchor
We're currently in the process of developing `v1.0` but there are still issues to iron out with the existing distro: `v0.x.x`. So we've compiled this short document to help you guys help *us*!

### What branch do I work from? How do I even pull request?
There seems to have been a bit of confusion in the past, and yes our branches are currently a bit confusing in structure. If you want to conrtibute to the current version of Anchor then please work from the `pre-1.0-develop` branch. This is where any features/refactoring should be happening. If what you're planning on doing is fixing a bug then please work from the `master` branch, this branch will always have the latest *tagged* release of Anchor. When you submit a bug fix PR we'll also merge it into the current version `dev` branch.

We're planning on cleaning everything up once `1.0` is released, by following the simple Gitflow system which can be found in many Git GUI's, in the git extras package and so on.

#### Feature branches
`feature/what-it-is`

#### Bugfix branches
`bugfix/what-it-is-fixing`

### Why won't you merge my PR?
This may be the case if you've not followed our guidelines, your code does not do as suggested, it doesn't work or if we simply haven't had the time to take a look at it properly and ensure it's working correctly.

### I've translated Anchor to 'X' language, how do I submit this?
Translations should be submitted to the official [anchor-translations](https://github.com/anchorcms/anchor-translations) repo, please check this repo before you start incase the translation has already been made.

### How to submit issues
Please follow the below points:
- Check if bug is consistent in different environments
- Check if its been reported and solved in the Anchor forum
- Check if there is an issue that already covers or relates to it
- Give an accurate and concise title to issue
- Prefix title of issue with `Bug:` or `Feature:`
- Explain bug briefly but in detail
- Give guidance on how to reproduce bug
- Add screenshots when possible

> ProTip: Check out [this example issue](https://github.com/anchorcms/anchor-cms/issues/873#issuecomment-151784603) for reference on good bug reporting.

### When writing your code, always follow standards
If you're familiar with open source software, you probably know how important coding guidelines and standards are. Most common are (in order of appearance) PEAR, Zend(Framework) and [PSR-1](http://www.php-fig.org/psr/psr-1)/[PSR-2](http://www.php-fig.org/psr/psr-2).

Since the [PHP-FIG](http://www.php-fig.org) and their standards gain more and more followers and Anchor uses composer, which supports PSR-0 and PSR-4, all code should follow [PSR-1](http://www.php-fig.org/psr/psr-1) and [PSR-2](http://www.php-fig.org/psr/psr-2).
More help on best practices for PHP development can be found on [PHP the right way](http://www.phptherightway.com).

When working on a file, always enforce coding standards for the whole file. This increases the consistency of the code with every contribution!
