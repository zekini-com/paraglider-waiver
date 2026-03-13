#!/usr/bin/env bash
#
# Install Git pre-commit hook for the project.
# Run once after cloning: bash scripts/setup-hooks.sh
#

set -euo pipefail

REPO_ROOT="$(cd "$(dirname "$0")/.." && pwd)"
HOOK_DIR="$REPO_ROOT/.git/hooks"
HOOK_FILE="$HOOK_DIR/pre-commit"

mkdir -p "$HOOK_DIR"

cat > "$HOOK_FILE" << 'HOOK'
#!/usr/bin/env bash
set -euo pipefail

echo "Running pre-commit checks..."

echo "=> Laravel Pint (code style)"
./vendor/bin/pint --test
if [ $? -ne 0 ]; then
    echo "Pint failed. Please run './vendor/bin/pint' to fix code style issues."
    exit 1
fi

echo "=> PHPStan (static analysis)"
./vendor/bin/phpstan analyse
if [ $? -ne 0 ]; then
    echo "PHPStan failed. Please fix the reported issues before committing."
    exit 1
fi

echo "All pre-commit checks passed."
HOOK

chmod +x "$HOOK_FILE"
echo "Git pre-commit hook installed at $HOOK_FILE"
