# GitHub Actions

It's possible to export and deploy static pages via [GitHub Actions](https://github.com/features/actions). Below is an example workflow file which deploys to [Netlify](netlify.md). This workflow requires two [GitHub Action Secrets](https://help.github.com/en/github/automating-your-workflow-with-github-actions/virtual-environments-for-github-actions#creating-and-using-secrets-encrypted-variables) you should add to your repository \(`NETLIFY_AUTH_TOKEN`, `NETLIFY_SITE_ID`\) more details in the [netlify/actions](https://github.com/netlify/actions/tree/master/cli) repo.

{% code-tabs %}
{% code-tabs-item title=".github/workflows/deploy.yml" %}
```yaml
name: Deploy

on:
  push:
    branches:
      - master

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - name: Git checkout
        uses: actions/checkout@v1
      - name: Copy .env
        run: php -r "file_exists('.env') || copy('.env.example', '.env');"
      - name: Install Composer Dependencies
        run: composer install --no-ansi --no-interaction --no-suggest --no-progress --prefer-dist
      - name: Install Yarn Dependencies
        run: yarn install
      - name: Generate key
        run: php artisan key:generate
      - name: Mix assets
        run: yarn production
      - name: Export page
        run: php artisan export --env=prod --skip-assets --skip-deploy
      - name: Netlify deploy
        uses: netlify/actions/cli@master
        with:
          args: deploy --prod
        env:
          NETLIFY_AUTH_TOKEN: ${{ secrets.NETLIFY_AUTH_TOKEN }}
          NETLIFY_SITE_ID: ${{ secrets.NETLIFY_SITE_ID }}
```
{% endcode-tabs-item %}
{% endcode-tabs %}

You should adjust it to your needs if you require any custom commands to be able to export your page.

