import re
import os
import subprocess
import sys

SQL_FILE = r'D:\Badra\project\belajar_vibecoding\VRM PROJECT\backup\vesselri_db.sql'
OUTPUT_SQL = r'D:\Badra\project\belajar_vibecoding\vrm-mib\database\migrated_data.sql'

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
    # Escape single quotes for PostgreSQL
    val_clean = val.replace("'", "''")
    return f"'{val_clean}'"

def clean_sql_num(val, default='NULL'):
    if val is None or val == 'NULL' or val == '':
        return default
    try:
        float(val)
        return str(val)
    except ValueError:
        return default

def clean_sql_date(val):
    if val is None or val == 'NULL' or val == '' or val.startswith('0000-00-00'):
        return 'NULL'
    return clean_sql_str(val)

def extract_table_inserts(sql_path):
    table_data = {}
    print(f"Reading {sql_path}...")
    with open(sql_path, 'r', encoding='latin1') as f:
        for line in f:
            if line.startswith('INSERT INTO'):
                parts = line.split()
                tbl = parts[2].replace('`', '').strip()
                val_idx = line.find('VALUES')
                if val_idx != -1:
                    val_str = line[val_idx + 6:].strip()
                    if val_str.endswith(';'):
                        val_str = val_str[:-1]
                    if tbl not in table_data:
                        table_data[tbl] = []
                    rows = parse_values(val_str)
                    table_data[tbl].extend(rows)
                    print(f"Loaded {len(rows)} rows for `{tbl}`")
    return table_data

def generate_postgres_migration():
    data = extract_table_inserts(SQL_FILE)
    
    out_lines = [
        "-- VRM Modern Platform: PostgreSQL Data Migration",
        "-- Auto-generated from legacy vesselri_db.sql",
        "BEGIN;",
        "SET CONSTRAINTS ALL DEFERRED;",
        ""
    ]
    
    # 1. COMPANIES
    print("Processing companies...")
    known_company_ids = set()
    if 'companies' in data:
        for row in data['companies']:
            # (`company_id`, `name`, `contact_info`, `pic`)
            cid = clean_sql_num(row[0])
            name = clean_sql_str(row[1])
            contact_info = clean_sql_str(row[2]) if len(row) > 2 else 'NULL'
            pic = clean_sql_str(row[3]) if len(row) > 3 else 'NULL'
            if cid != 'NULL':
                known_company_ids.add(int(cid))
                out_lines.append(f"INSERT INTO companies (id, name, type, contact_info, pic, created_at, updated_at) "
                                 f"VALUES ({cid}, {name}, 'owner', {contact_info}, {pic}, NOW(), NOW()) "
                                 f"ON CONFLICT (id) DO UPDATE SET name = EXCLUDED.name, contact_info = EXCLUDED.contact_info, pic = EXCLUDED.pic;")

    # 2. INSURANCE COMPANIES
    print("Processing insurance companies...")
    known_ins_ids = set()
    if 'insurance_company' in data:
        for row in data['insurance_company']:
            # (`id`, `name`, `address`, `phone`, `email`, `website`)
            iid = clean_sql_num(row[0])
            name = clean_sql_str(row[1])
            address = clean_sql_str(row[2]) if len(row) > 2 else 'NULL'
            phone = clean_sql_str(row[3]) if len(row) > 3 else 'NULL'
            email = clean_sql_str(row[4]) if len(row) > 4 else 'NULL'
            website = clean_sql_str(row[5]) if len(row) > 5 else 'NULL'
            if iid != 'NULL':
                known_ins_ids.add(int(iid))
                out_lines.append(f"INSERT INTO insurance_companies (id, name, address, phone, email, website, created_at, updated_at) "
                                 f"VALUES ({iid}, {name}, {address}, {phone}, {email}, {website}, NOW(), NOW()) "
                                 f"ON CONFLICT (id) DO UPDATE SET name = EXCLUDED.name, address = EXCLUDED.address;")

    # 3. BROKERS
    print("Processing brokers...")
    known_broker_ids = set()
    if 'broker' in data:
        for row in data['broker']:
            # (`id`, `name`, `address`, `phone`, `email`, `website`)
            bid = clean_sql_num(row[0])
            name = clean_sql_str(row[1])
            address = clean_sql_str(row[2]) if len(row) > 2 else 'NULL'
            phone = clean_sql_str(row[3]) if len(row) > 3 else 'NULL'
            email = clean_sql_str(row[4]) if len(row) > 4 else 'NULL'
            website = clean_sql_str(row[5]) if len(row) > 5 else 'NULL'
            if bid != 'NULL':
                known_broker_ids.add(int(bid))
                out_lines.append(f"INSERT INTO brokers (id, name, address, phone, email, website, created_at, updated_at) "
                                 f"VALUES ({bid}, {name}, {address}, {phone}, {email}, {website}, NOW(), NOW()) "
                                 f"ON CONFLICT (id) DO UPDATE SET name = EXCLUDED.name, address = EXCLUDED.address;")

    # 4. SURVEYORS
    print("Processing surveyors...")
    known_surveyor_ids = set()
    if 'surveyors' in data:
        for row in data['surveyors']:
            # (`surveyor_id`, `name`, `contact_info`)
            sid = clean_sql_num(row[0])
            name = clean_sql_str(row[1])
            contact_info = clean_sql_str(row[2]) if len(row) > 2 else 'NULL'
            if sid != 'NULL':
                known_surveyor_ids.add(int(sid))
                out_lines.append(f"INSERT INTO surveyors (id, name, contact_info, created_at, updated_at) "
                                 f"VALUES ({sid}, {name}, {contact_info}, NOW(), NOW()) "
                                 f"ON CONFLICT (id) DO UPDATE SET name = EXCLUDED.name, contact_info = EXCLUDED.contact_info;")

    # 5. VESSELS
    print("Processing vessels...")
    known_vessel_ids = set()
    if 'vessels' in data:
        for row in data['vessels']:
            # (`vessel_id`, `name`, `ex_name`, `imo_number`, `flag`, `type`, `owner_company_id`, `operator_company_id`,
            #  `classification_society`, `class_recommendation_issued`, `exemption_certificates_issued`,
            #  `year_built`, `place_built`, `grt`, `dwt`, `tsi_old`, `tsi`, `dimensions`, `recent_cargoes_carried`,
            #  `recent_port_of_call`, `trading_area`, `date_of_last_drydock`, `date_of_next_drydock`)
            vid = clean_sql_num(row[0])
            name = clean_sql_str(row[1])
            ex_name = clean_sql_str(row[2]) if len(row) > 2 else 'NULL'
            imo_number = clean_sql_str(row[3]) if len(row) > 3 else 'NULL'
            flag = clean_sql_str(row[4]) if len(row) > 4 else 'NULL'
            type_name = clean_sql_str(row[5]) if len(row) > 5 else 'NULL'
            
            owner_id = clean_sql_num(row[6]) if len(row) > 6 else 'NULL'
            if owner_id != 'NULL' and int(owner_id) not in known_company_ids:
                owner_id = 'NULL'
                
            operator_id = clean_sql_num(row[7]) if len(row) > 7 else 'NULL'
            if operator_id != 'NULL' and int(operator_id) not in known_company_ids:
                operator_id = 'NULL'
                
            class_society = clean_sql_str(row[8]) if len(row) > 8 else 'NULL'
            class_rec = clean_sql_str(row[9]) if len(row) > 9 else 'NULL'
            exemption = clean_sql_str(row[10]) if len(row) > 10 else 'NULL'
            year_built = clean_sql_num(row[11]) if len(row) > 11 else 'NULL'
            place_built = clean_sql_str(row[12]) if len(row) > 12 else 'NULL'
            grt = clean_sql_num(row[13]) if len(row) > 13 else 'NULL'
            dwt = clean_sql_num(row[14]) if len(row) > 14 else 'NULL'
            tsi = clean_sql_num(row[16], default='0') if len(row) > 16 else '0'
            dimensions = clean_sql_str(row[17]) if len(row) > 17 else 'NULL'
            recent_cargoes = clean_sql_str(row[18]) if len(row) > 18 else 'NULL'
            recent_port = clean_sql_str(row[19]) if len(row) > 19 else 'NULL'
            trading_area = clean_sql_str(row[20]) if len(row) > 20 else 'NULL'
            last_drydock = clean_sql_date(row[21]) if len(row) > 21 else 'NULL'
            next_drydock = clean_sql_date(row[22]) if len(row) > 22 else 'NULL'
            
            if vid != 'NULL':
                known_vessel_ids.add(int(vid))
                out_lines.append(
                    f"INSERT INTO vessels (id, name, ex_name, imo_number, flag, type_name_legacy, owner_company_id, operator_company_id, "
                    f"classification_society, class_recommendation_issued, exemption_certificates_issued, year_built, place_built, "
                    f"grt, dwt, tsi, dimensions, recent_cargoes_carried, recent_port_of_call, trading_area, date_of_last_drydock, date_of_next_drydock, created_at, updated_at) "
                    f"VALUES ({vid}, {name}, {ex_name}, {imo_number}, {flag}, {type_name}, {owner_id}, {operator_id}, "
                    f"{class_society}, {class_rec}, {exemption}, {year_built}, {place_built}, "
                    f"{grt}, {dwt}, {tsi}, {dimensions}, {recent_cargoes}, {recent_port}, {trading_area}, {last_drydock}, {next_drydock}, NOW(), NOW()) "
                    f"ON CONFLICT (id) DO UPDATE SET name = EXCLUDED.name, imo_number = EXCLUDED.imo_number, flag = EXCLUDED.flag, tsi = EXCLUDED.tsi;"
                )

    # 6. SURVEYS
    print("Processing surveys...")
    known_survey_ids = set()
    condition_map = {1: 'Self Assessment', 2: 'Photo Album', 3: 'Survey', 4: 'Underwriting'}
    status_map = {1: 'Outstanding', 2: 'Completed', 3: 'Cancelled'}
    
    if 'surveys' in data:
        for row in data['surveys']:
            # (`survey_id`, `vessel_id`, `surveyor_id`, `broker_id`, `insurance_product_id`, `surveyor_name`,
            #  `concern_from_surveyor`, `resume_from_surveyor`, `location`, `id_condition`, `due_date`,
            #  `date_of_survey`, `summary`, `survey_status`, `survey_status_form`, `datecreate`, `dateupdate`,
            #  `id_create`, `id_update`, `date_of_issue_deficiency`, `policy_period_start`, `policy_period_end`)
            sid = clean_sql_num(row[0])
            vid = clean_sql_num(row[1])
            
            # Skip survey if vessel is not in database
            if vid == 'NULL' or int(vid) not in known_vessel_ids:
                continue
                
            surveyor_id = clean_sql_num(row[2]) if len(row) > 2 else 'NULL'
            if surveyor_id != 'NULL' and int(surveyor_id) not in known_surveyor_ids:
                surveyor_id = 'NULL'
                
            broker_id = clean_sql_num(row[3]) if len(row) > 3 else 'NULL'
            if broker_id != 'NULL' and int(broker_id) not in known_broker_ids:
                broker_id = 'NULL'
                
            surveyor_name = clean_sql_str(row[5]) if len(row) > 5 else 'NULL'
            concern = clean_sql_str(row[6]) if len(row) > 6 else 'NULL'
            resume = clean_sql_str(row[7]) if len(row) > 7 else 'NULL'
            location = clean_sql_str(row[8]) if len(row) > 8 else 'NULL'
            
            # Condition mapping
            raw_cond = row[9] if len(row) > 9 else '3'
            cond_type = condition_map.get(int(raw_cond) if raw_cond.isdigit() else 3, 'Survey')
            
            due_date = clean_sql_date(row[10]) if len(row) > 10 else 'NULL'
            date_of_survey = clean_sql_date(row[11]) if len(row) > 11 else 'NULL'
            summary = clean_sql_str(row[12]) if len(row) > 12 else 'NULL'
            
            # Status mapping
            raw_status = row[13] if len(row) > 13 else '1'
            status_text = status_map.get(int(raw_status) if raw_status.isdigit() else 1, 'Outstanding')
            
            form_status = clean_sql_str(row[14]) if len(row) > 14 and row[14] else "'draft'"
            policy_start = clean_sql_date(row[20]) if len(row) > 20 else 'NULL'
            policy_end = clean_sql_date(row[21]) if len(row) > 21 else 'NULL'
            
            if sid != 'NULL':
                known_survey_ids.add(int(sid))
                out_lines.append(
                    f"INSERT INTO surveys (id, vessel_id, surveyor_id, broker_id, surveyor_name, condition_type, status, form_status, "
                    f"location, date_of_survey, due_date, concern_from_surveyor, resume_from_surveyor, summary, policy_period_start, policy_period_end, created_at, updated_at) "
                    f"VALUES ({sid}, {vid}, {surveyor_id}, {broker_id}, {surveyor_name}, '{cond_type}', '{status_text}', {form_status}, "
                    f"{location}, {date_of_survey}, {due_date}, {concern}, {resume}, {summary}, {policy_start}, {policy_end}, NOW(), NOW()) "
                    f"ON CONFLICT (id) DO UPDATE SET status = EXCLUDED.status, summary = EXCLUDED.summary;"
                )

    # 7. DEFECTS / DEFICIENCIES
    print("Processing survey defects...")
    defect_status_map = {1: 'Outstanding', 2: 'Approve', 3: 'Decline'}
    if 'survey_responses_deffect' in data:
        for row in data['survey_responses_deffect']:
            # (`id_deffect`, `survey_id`, `item_id`, `explanation`, `recommendation_rectification`,
            #  `rectification_owner`, `rectification_time`, `remark_deffect`, `status_remark`, `id_status`, `id_status_rectification`, `sumber`, `id_deffect_manual`)
            did = clean_sql_num(row[0])
            sid = clean_sql_num(row[1])
            
            if sid == 'NULL' or int(sid) not in known_survey_ids:
                continue
                
            explanation = clean_sql_str(row[3]) if len(row) > 3 else 'NULL'
            rec_rect = clean_sql_str(row[4]) if len(row) > 4 else 'NULL'
            owner_rect = clean_sql_str(row[5]) if len(row) > 5 else 'NULL'
            rect_days = clean_sql_num(row[6]) if len(row) > 6 else 'NULL'
            remark = clean_sql_str(row[7]) if len(row) > 7 else 'NULL'
            
            raw_stat = row[9] if len(row) > 9 else '1'
            stat_name = defect_status_map.get(int(raw_stat) if raw_stat.isdigit() else 1, 'Outstanding')
            source = clean_sql_str(row[11]) if len(row) > 11 else "'hasil_survey'"
            
            if did != 'NULL':
                out_lines.append(
                    f"INSERT INTO survey_defects (id, survey_id, explanation, recommendation_rectification, rectification_owner, "
                    f"rectification_time_days, remark_defect, status, source, created_at, updated_at) "
                    f"VALUES ({did}, {sid}, {explanation}, {rec_rect}, {owner_rect}, {rect_days}, {remark}, '{stat_name}', {source}, NOW(), NOW()) "
                    f"ON CONFLICT (id) DO NOTHING;"
                )

    # Sequence resets for PostgreSQL auto-increments
    out_lines.extend([
        "",
        "-- Reset sequences to highest id values",
        "SELECT setval(pg_get_serial_sequence('companies', 'id'), COALESCE(max(id), 1)) FROM companies;",
        "SELECT setval(pg_get_serial_sequence('insurance_companies', 'id'), COALESCE(max(id), 1)) FROM insurance_companies;",
        "SELECT setval(pg_get_serial_sequence('brokers', 'id'), COALESCE(max(id), 1)) FROM brokers;",
        "SELECT setval(pg_get_serial_sequence('surveyors', 'id'), COALESCE(max(id), 1)) FROM surveyors;",
        "SELECT setval(pg_get_serial_sequence('vessels', 'id'), COALESCE(max(id), 1)) FROM vessels;",
        "SELECT setval(pg_get_serial_sequence('surveys', 'id'), COALESCE(max(id), 1)) FROM surveys;",
        "SELECT setval(pg_get_serial_sequence('survey_defects', 'id'), COALESCE(max(id), 1)) FROM survey_defects;",
        "COMMIT;",
        ""
    ])
    
    with open(OUTPUT_SQL, 'w', encoding='utf-8') as f:
        f.write("\n".join(out_lines))
        
    print(f"Generated PostgreSQL migration file with {len(out_lines)} lines at {OUTPUT_SQL}")
    print(f"Total Companies: {len(known_company_ids)}")
    print(f"Total Vessels: {len(known_vessel_ids)}")
    print(f"Total Surveys: {len(known_survey_ids)}")

if __name__ == '__main__':
    generate_postgres_migration()
