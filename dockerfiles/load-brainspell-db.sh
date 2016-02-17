#!/bin/bash
echo "Uploading brainspell database..."
cat ../brainspell_sanitized.sql | docker exec -i brainspell "/usr/bin/mysql" -u root --password=beo8hkii

