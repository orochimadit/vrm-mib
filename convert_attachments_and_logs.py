import re
import os
import subprocess
import sys

SQL_FILE = r'D:\Badra\project\belajar_vibecoding\VRM PROJECT\backup\vesselri_db.sql'
OUTPUT_SQL = r'D:\Badra\project\belajar_vibecoding\vrm-mib\database\migrated_attachments_and_logs.sql'

def parse_values(values_str):
    rows = []
    current_val = []
    in_string = False
    escape = False
    quote_char = None
    current_token = []
    depth = 0

    i = 0
    n = len(values_str)
    while i < n:
        c = values_str[i]
        
        if escape:
            current_token.append(c)
            escape = False
            i += 1
            continue
            
        if c == '\\':
            escape = True
            i += 1
            continue
            
        if in_string:
            if c == quote_char:
                if i + 1 < n and values_str[i+1] == quote_char:
                    current_token.append("''")
                    i += 2
                    continue
                else:
                    in_string = False
                    quote_char = None
            else:
                current_token.append(c)
            i += 1
            continue
            
        if c in ("'", '"'):
            in_string = True
            quote_char = c
            i += 1
            continue
            
        if c == '(':
            depth += 1
            current_val = []
            current_token = []
            i += 1
            continue
            
        if c == ')':
            depth -= 1
            val = "".join(current_token).strip()
            current_val.append(val)
            rows.append(current_val)
            current_val = []
            current_token = []
            i += 1
            continue
            
        if c == ',' and depth == 1:
            val = "".join(current_token).strip()
            current_val.append(val)
            current_token = []
            i += 1
            continue
            
        current_token.append(c)
        i += 1
        
    return rows

def clean_sql_str(val):
    if val is None or val == 'NULL' or val == '':
        return 'NULL'
    val = val.strip()
    if val.upper() == 'NULL':
        return 'NULL'
    escaped = val.replace("'", "''")
    return f"'{escaped}'"

def clean_sql_num(val):
    if val is None or val == 'NULL' or val == '':
        return 'NULL'
    val = val.strip().replace("'", "").replace('"', '')
    if not val or val.upper() == 'NULL':
        return 'NULL'
    try:
        return str(int(val))
    except ValueError:
        try:
            return str(float(val))
        except ValueError:
            return 'NULL'

def clean_sql_timestamp(val):
    if val is None or val == 'NULL' or val == '':
        return 'NULL'
    val = val.strip().replace("'", "").replace('"', '')
    if not val or val.upper() == 'NULL' or val == '0000-00-00 00:00:00':
        return 'NULL'
    return f"'{val}'"

def generate_postgres_migration():
    print(f"Reading {SQL_FILE}...")
    
    target_tables = [
        'survey_items',
        'survey_responses',
        'survey_responses_attachment',
        'selfassessment_attachment',
        'photoalbum_attachment',
        'survey_responses_deffect_file',
        'log_rectification',
        'log_rectification_file'
    ]
    
    data = {t: [] for t in target_tables}
    
    with open(SQL_FILE, 'r', encoding='utf-8', errors='ignore') as f:
        for line in f:
            for t in target_tables:
                prefix = f"INSERT INTO `{t}`"
                if line.startswith(prefix):
                    val_idx = line.find('VALUES')
                    if val_idx != -1:
                        values_part = line[val_idx + 6:].rstrip(';\r\n ')
                        parsed_rows = parse_values(values_part)
                        data[t].extend(parsed_rows)
                        
    out_lines = [
        "-- MIGRATED ATTACHMENTS AND LOGS FOR VRM MODERN",
        "BEGIN;",
        ""
    ]

    # 1. SURVEY ITEMS
    print("Processing survey_items...")
    for row in data['survey_items']:
        # item_id, insurance_product_id, ref_no, category, item_name, risk_value_expected, risk_value
        iid = clean_sql_num(row[0])
        prod_id = clean_sql_num(row[1]) if len(row) > 1 else 'NULL'
        ref_no = clean_sql_str(row[2]) if len(row) > 2 else 'NULL'
        cat = clean_sql_str(row[3]) if len(row) > 3 else 'NULL'
        name = clean_sql_str(row[4]) if len(row) > 4 else 'NULL'
        exp = clean_sql_str(row[5]) if len(row) > 5 else 'NULL'
        val = clean_sql_num(row[6]) if len(row) > 6 else 'NULL'
        if iid != 'NULL':
            out_lines.append(
                f"INSERT INTO survey_items (item_id, insurance_product_id, ref_no, category, item_name, risk_value_expected, risk_value, created_at, updated_at) "
                f"VALUES ({iid}, {prod_id}, {ref_no}, {cat}, {name}, {exp}, {val}, NOW(), NOW()) "
                f"ON CONFLICT (item_id) DO NOTHING;"
            )

    # 2. SURVEY RESPONSES
    print("Processing survey_responses...")
    for row in data['survey_responses']:
        # response_id, survey_id, item_id, response, risk_impact, risk_rating, remarks_from_surveyor
        rid = clean_sql_num(row[0])
        sid = clean_sql_num(row[1]) if len(row) > 1 else 'NULL'
        iid = clean_sql_num(row[2]) if len(row) > 2 else 'NULL'
        resp = clean_sql_str(row[3]) if len(row) > 3 else 'NULL'
        impact = clean_sql_str(row[4]) if len(row) > 4 else 'NULL'
        rating = clean_sql_num(row[5]) if len(row) > 5 else 'NULL'
        remarks = clean_sql_str(row[6]) if len(row) > 6 else 'NULL'
        if rid != 'NULL':
            out_lines.append(
                f"INSERT INTO survey_responses (response_id, survey_id, item_id, response, risk_impact, risk_rating, remarks_from_surveyor, created_at, updated_at) "
                f"VALUES ({rid}, {sid}, {iid}, {resp}, {impact}, {rating}, {remarks}, NOW(), NOW()) "
                f"ON CONFLICT (response_id) DO NOTHING;"
            )

    # 3. SURVEY RESPONSES ATTACHMENT
    print("Processing survey_responses_attachment...")
    for row in data['survey_responses_attachment']:
        # id_attachment, response_id, title, type, file, link, gdrive_id
        aid = clean_sql_num(row[0])
        rid = clean_sql_num(row[1]) if len(row) > 1 else 'NULL'
        title = clean_sql_str(row[2]) if len(row) > 2 else 'NULL'
        atype = clean_sql_str(row[3]) if len(row) > 3 else 'NULL'
        afile = clean_sql_str(row[4]) if len(row) > 4 else 'NULL'
        link = clean_sql_str(row[5]) if len(row) > 5 else 'NULL'
        gid = clean_sql_str(row[6]) if len(row) > 6 else 'NULL'
        if aid != 'NULL':
            out_lines.append(
                f"INSERT INTO survey_responses_attachment (id_attachment, response_id, title, type, file, link, gdrive_id, created_at, updated_at) "
                f"VALUES ({aid}, {rid}, {title}, {atype}, {afile}, {link}, {gid}, NOW(), NOW()) "
                f"ON CONFLICT (id_attachment) DO NOTHING;"
            )

    # 4. SELF ASSESSMENT ATTACHMENT
    print("Processing selfassessment_attachment...")
    for row in data['selfassessment_attachment']:
        aid = clean_sql_num(row[0])
        rid = clean_sql_num(row[1]) if len(row) > 1 else 'NULL'
        title = clean_sql_str(row[2]) if len(row) > 2 else 'NULL'
        atype = clean_sql_str(row[3]) if len(row) > 3 else 'NULL'
        afile = clean_sql_str(row[4]) if len(row) > 4 else 'NULL'
        link = clean_sql_str(row[5]) if len(row) > 5 else 'NULL'
        gid = clean_sql_str(row[6]) if len(row) > 6 else 'NULL'
        if aid != 'NULL':
            out_lines.append(
                f"INSERT INTO selfassessment_attachment (id_attachment, response_id, title, type, file, link, gdrive_id, created_at, updated_at) "
                f"VALUES ({aid}, {rid}, {title}, {atype}, {afile}, {link}, {gid}, NOW(), NOW()) "
                f"ON CONFLICT (id_attachment) DO NOTHING;"
            )

    # 5. PHOTO ALBUM ATTACHMENT
    print("Processing photoalbum_attachment...")
    for row in data['photoalbum_attachment']:
        aid = clean_sql_num(row[0])
        rid = clean_sql_num(row[1]) if len(row) > 1 else 'NULL'
        title = clean_sql_str(row[2]) if len(row) > 2 else 'NULL'
        atype = clean_sql_str(row[3]) if len(row) > 3 else 'NULL'
        afile = clean_sql_str(row[4]) if len(row) > 4 else 'NULL'
        link = clean_sql_str(row[5]) if len(row) > 5 else 'NULL'
        gid = clean_sql_str(row[6]) if len(row) > 6 else 'NULL'
        if aid != 'NULL':
            out_lines.append(
                f"INSERT INTO photoalbum_attachment (id_attachment, response_id, title, type, file, link, gdrive_id, created_at, updated_at) "
                f"VALUES ({aid}, {rid}, {title}, {atype}, {afile}, {link}, {gid}, NOW(), NOW()) "
                f"ON CONFLICT (id_attachment) DO NOTHING;"
            )

    # 6. SURVEY DEFECT FILES (survey_responses_deffect_file)
    print("Processing survey_responses_deffect_file...")
    for row in data['survey_responses_deffect_file']:
        # id_file, id_deffect, type, file, title, link, gdrive_id
        fid = clean_sql_num(row[0])
        did = clean_sql_num(row[1]) if len(row) > 1 else 'NULL'
        ftype = clean_sql_str(row[2]) if len(row) > 2 else 'NULL'
        ffile = clean_sql_str(row[3]) if len(row) > 3 else 'NULL'
        title = clean_sql_str(row[4]) if len(row) > 4 else 'NULL'
        link = clean_sql_str(row[5]) if len(row) > 5 else 'NULL'
        gid = clean_sql_str(row[6]) if len(row) > 6 else 'NULL'
        if fid != 'NULL':
            out_lines.append(
                f"INSERT INTO survey_responses_deffect_file (id_file, id_deffect, type, file, title, link, gdrive_id, created_at, updated_at) "
                f"VALUES ({fid}, {did}, {ftype}, {ffile}, {title}, {link}, {gid}, NOW(), NOW()) "
                f"ON CONFLICT (id_file) DO NOTHING;"
            )

    # 7. LOG RECTIFICATION
    print("Processing log_rectification...")
    for row in data['log_rectification']:
        # id, id_deffect, value, category, datetime, id_admin
        lid = clean_sql_num(row[0])
        did = clean_sql_num(row[1]) if len(row) > 1 else 'NULL'
        val = clean_sql_str(row[2]) if len(row) > 2 else 'NULL'
        cat = clean_sql_str(row[3]) if len(row) > 3 else "'rectification_owner'"
        dt = clean_sql_timestamp(row[4]) if len(row) > 4 else 'NOW()'
        adm = clean_sql_num(row[5]) if len(row) > 5 else 'NULL'
        if lid != 'NULL':
            out_lines.append(
                f"INSERT INTO log_rectification (id, id_deffect, value, category, datetime, id_admin, created_at, updated_at) "
                f"VALUES ({lid}, {did}, {val}, {cat}, {dt}, {adm}, NOW(), NOW()) "
                f"ON CONFLICT (id) DO NOTHING;"
            )

    # 8. LOG RECTIFICATION FILE
    print("Processing log_rectification_file...")
    for row in data['log_rectification_file']:
        # id_log_file, id_log_deffect, file_type, file_name, file_title, file_link, file_gdrive_id, datetime
        lfid = clean_sql_num(row[0])
        ldid = clean_sql_num(row[1]) if len(row) > 1 else 'NULL'
        ftype = clean_sql_str(row[2]) if len(row) > 2 else 'NULL'
        fname = clean_sql_str(row[3]) if len(row) > 3 else 'NULL'
        title = clean_sql_str(row[4]) if len(row) > 4 else 'NULL'
        link = clean_sql_str(row[5]) if len(row) > 5 else 'NULL'
        gid = clean_sql_str(row[6]) if len(row) > 6 else 'NULL'
        dt = clean_sql_timestamp(row[7]) if len(row) > 7 else 'NOW()'
        if lfid != 'NULL':
            out_lines.append(
                f"INSERT INTO log_rectification_file (id_log_file, id_log_deffect, file_type, file_name, file_title, file_link, file_gdrive_id, datetime, created_at, updated_at) "
                f"VALUES ({lfid}, {ldid}, {ftype}, {fname}, {title}, {link}, {gid}, {dt}, NOW(), NOW()) "
                f"ON CONFLICT (id_log_file) DO NOTHING;"
            )

    # Sequence resets
    out_lines.extend([
        "",
        "-- Reset sequences to highest id values",
        "SELECT setval(pg_get_serial_sequence('survey_items', 'item_id'), COALESCE(max(item_id), 1)) FROM survey_items;",
        "SELECT setval(pg_get_serial_sequence('survey_responses', 'response_id'), COALESCE(max(response_id), 1)) FROM survey_responses;",
        "SELECT setval(pg_get_serial_sequence('survey_responses_attachment', 'id_attachment'), COALESCE(max(id_attachment), 1)) FROM survey_responses_attachment;",
        "SELECT setval(pg_get_serial_sequence('selfassessment_attachment', 'id_attachment'), COALESCE(max(id_attachment), 1)) FROM selfassessment_attachment;",
        "SELECT setval(pg_get_serial_sequence('photoalbum_attachment', 'id_attachment'), COALESCE(max(id_attachment), 1)) FROM photoalbum_attachment;",
        "SELECT setval(pg_get_serial_sequence('survey_responses_deffect_file', 'id_file'), COALESCE(max(id_file), 1)) FROM survey_responses_deffect_file;",
        "SELECT setval(pg_get_serial_sequence('log_rectification', 'id'), COALESCE(max(id), 1)) FROM log_rectification;",
        "SELECT setval(pg_get_serial_sequence('log_rectification_file', 'id_log_file'), COALESCE(max(id_log_file), 1)) FROM log_rectification_file;",
        "COMMIT;",
        ""
    ])

    with open(OUTPUT_SQL, 'w', encoding='utf-8') as f:
        f.write("\n".join(out_lines))

    print(f"Generated SQL file with {len(out_lines)} lines at {OUTPUT_SQL}")

if __name__ == '__main__':
    generate_postgres_migration()
