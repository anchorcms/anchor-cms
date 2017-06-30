# Contributing to Anchor
We're currently in the process of developing `v1.0` but there are still issues to iron out with the existing distro: `v0.x.x`. So we've compiled this short document to help you guys help *us*! (we need help lol)

### What branch do I work from? How do I even pull request?
These are the branches in this project, and what they're for.
- `master`
  - This branch is for the current release of anchor, so 0.* releases. If you wish to submit a PR with a fix, please do it from this branch.
- `pre-1.0-develop`
  - This branch is for active development on the current relase, so 0.* etc. If you wish to PR with a feature that doesn't make a fix, please do so to this branch, but please make sure your branches are up to date.
- `develop`
  - This is for active development on the next major release of Anchor, so 1.0+, feel free to take a look at this, though please don't rely on this for your projects as it's still a WIP.

We're planning on cleaning everything up once `1.0` is released, so if you're able to follow these guidelines, it will make things much easier for us.

#### Feature branches
`feature/what-it-is`

#### Bugfix branches
`bugfix/what-it-is-fixing`

### Why won't you merge my PR?
This may be the case if you've not followed our guidelines, your code does not do as suggested, it doesn't work or if we simply haven't had the time to take a look at it properly and ensure it's working correctly.

### I've translated Anchor to 'X' language, how do I submit this?
Translations should be submitted to the official [anchor-translations](https://github.com/anchorcms/anchor-translations) repo, please check this repo before you start incase the translation has already been made.

### How to submit feature requests
If you would like to add submit a feature request then please go to our [Feathub page](http://feathub.com/anchorcms/anchor-cms). If it's not already on the list then please add it and provide a description in the comments section. If it already exists then please give the existing entry a thumbs up. :+1:

### How to submit issues
Please follow the below points:
- Check if bug is consistent in different environments
- Check if its been reported and solved in the Anchor forum
- **Check if there is an issue that already covers or relates to it** (including closed issues)
- Give an accurate and concise title to issue
- Prefix title of issue with `Bug:` or `Feature:`
- Explain bug briefly but in detail
- Give guidance on how to reproduce bug
- Add screenshots when possible
- Follow the templates that autofill the editor - **failure to follow the template will result in closure of the issue without resolution**

> ProTip: Check out [this example issue](https://github.com/anchorcms/anchor-cms/issues/873#issuecomment-151784603) for reference on good bug reporting.

Keep in mind that when you submit an issue, you are the maintainer of that issue. The team or community may ask for more detail on specifics about the issue, so cooperating is always great. However, due to there being a large number of issue reports that suddenly become inactive, we request that you keep checking back.

If the issue remains inactive (or *dormant*) for two to four weeks (depending on the issue), we may close it, assuming that it's been fixed. If you just happen to be late back to the party, simply bump the issue with a comment making sure to include any updates.

***It is important to note that this is for house cleaning purposes, rather than for shrugging off issues. Cooperation is requested.***

### When writing your code, always follow standards
If you're familiar with open source software, you probably know how important coding guidelines and standards are. Most common are (in order of appearance) PEAR, Zend(Framework) and [PSR-1](http://www.php-fig.org/psr/psr-1)/[PSR-2](http://www.php-fig.org/psr/psr-2).

Since the [PHP-FIG](http://www.php-fig.org) and their standards gain more and more followers and Anchor uses composer, which supports PSR-0 and PSR-4, all code should follow [PSR-1](http://www.php-fig.org/psr/psr-1) and [PSR-2](http://www.php-fig.org/psr/psr-2).
More help on best practices for PHP development can be found on [PHP the right way](http://www.phptherightway.com).

When working on a file, always enforce coding standards for the whole file. This increases the consistency of the code with every contribution! *If you don't, we may reject your pull requests.*
