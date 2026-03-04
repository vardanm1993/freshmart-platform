#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
FRONT_SRC="$ROOT_DIR/frontend/src/shared/i18n/locales/en.json"
BACK_DST="$ROOT_DIR/backend/resources/i18n/source/en.json"

mkdir -p "$(dirname "$BACK_DST")"
cp "$FRONT_SRC" "$BACK_DST"
echo "Synced: $FRONT_SRC -> $BACK_DST"
