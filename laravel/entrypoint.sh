#!/bin/bash
cd "$(dirname "${0}")/example-app" || exit 1

# 準備 (毎度流れるのは大いなる無駄である see example-app/composer.json)
composer run setup

# .envのDB関連設定 渡ってきた環境変数で書き換える
while IFS='=' read -r key dotenv_value; do
    if [[ $(env | grep -c "${key}") = 0 ]]; then
        eval "env_value=${dotenv_value}"
    else
        eval "env_value=\${$key}"
    fi
    # shellcheck disable=SC2154
    sed -i "s/^${key}=.*/${key}=${env_value}/" .env
done < <(grep '^DB_' .env)

# 起動 (正直無駄なものも動いている see example-app/composer.json)
composer run dev
