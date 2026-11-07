import sys
sys.path.append(r'D:\Badra\project\belajar_vibecoding\vrm-mib')
from convert_and_import import parse_values, clean_sql_str, clean_sql_num

SQL_FILE = r'D:\Badra\project\belajar_vibecoding\VRM PROJECT\backup\vesselri_db.sql'
OUTPUT_SQL = r'D:\Badra\project\belajar_vibecoding\vrm-mib\database\migrated_users.sql'

role_map = {
    1: 'admin_mcm',
    2: 'insurance',
    3: 'surveyor',
    4: 'shipowner',
    5: 'broker'
}

print("Parsing legacy admin table...")
out_lines = [
    "-- Migrated Users & Roles from legacy admin table",
    "BEGIN;",
    ""
]

seen_usernames = set()
seen_emails = set()

# Keep default admin
seen_emails.add('admin@vesselriskmanagement.com')

with open(SQL_FILE, 'r', encoding='latin1') as f:
    for line in f:
        if line.startswith('INSERT INTO') and (chr(96) + 'admin' + chr(96)) in line:
            val_idx = line.find('VALUES')
            val_str = line[val_idx + 6:].strip().rstrip(';')
            rows = parse_values(val_str)
            print(f"Found {len(rows)} admin records")
            
            for r in rows:
                # (`id_admin`, `pass`, `username`, `nama_lengkap`, `avatar`, `banned`, `id_level`, `id_level_reference`, ...)
                uid = r[0]
                pwd_hash = r[1]
                uname = r[2].strip()
                full_name = r[3].strip() if len(r) > 3 and r[3].strip() else uname
                banned = r[5] if len(r) > 5 else '0'
                raw_level = r[6] if len(r) > 6 else '4'
                ref_id = clean_sql_num(r[7]) if len(r) > 7 else 'NULL'
                
                lvl_int = int(raw_level) if raw_level.isdigit() else 4
                role_name = role_map.get(lvl_int, 'shipowner')
                is_active = 'false' if banned == '1' else 'true'
                
                # Clean username
                if not uname:
                    continue
                    
                # Handle unique username
                clean_uname = uname.lower().replace(' ', '_')
                if clean_uname in seen_usernames:
                    clean_uname = f"{clean_uname}_{uid}"
                seen_usernames.add(clean_uname)
                
                # Email
                email = f"{clean_uname}@vesselriskmanagement.com"
                if email in seen_emails:
                    email = f"{clean_uname}_{uid}@vesselriskmanagement.com"
                seen_emails.add(email)
                
                name_sql = clean_sql_str(full_name)
                uname_sql = clean_sql_str(clean_uname)
                email_sql = clean_sql_str(email)
                pwd_sql = clean_sql_str(pwd_hash)
                
                out_lines.append(
                    f"INSERT INTO users (name, username, email, password, role, id_level_legacy, reference_id, is_active, created_at, updated_at) "
                    f"VALUES ({name_sql}, {uname_sql}, {email_sql}, {pwd_sql}, '{role_name}', {lvl_int}, {ref_id}, {is_active}, NOW(), NOW()) "
                    f"ON CONFLICT (email) DO UPDATE SET "
                    f"name = EXCLUDED.name, username = EXCLUDED.username, role = EXCLUDED.role, password = EXCLUDED.password;"
                )
            break

out_lines.extend([
    "",
    "COMMIT;",
    ""
])

with open(OUTPUT_SQL, 'w', encoding='utf-8') as f:
    f.write("\n".join(out_lines))

print(f"Generated {len(out_lines)} lines in {OUTPUT_SQL}")
