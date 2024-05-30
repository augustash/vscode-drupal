# Setup

In the site root, run:

```bash

composer config --json --merge extra.installer-paths '{".vscode/extensions/{$name}": ["type:vscode-extension"]}' && composer config --json --merge extra.installer-types '["vscode-extension"]' && composer config scripts.vscode-setup "VscodeDrupal\\Install::postPackageInstall" && composer require augustash/vscode-drupal && composer vscode-setup

```
