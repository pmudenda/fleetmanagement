CREATE TABLE REF_COST_CENTERS
(
    code_cost_center          nvarchar2(10),
    description               nvarchar2(250),
    code_cost_center_superior nvarchar2(10),
    indicator_last_level      nvarchar2(2),
    status                    nvarchar2(2)
);

INSERT INTO REF_COST_CENTERS
VALUES ('12134',
        'STATISTIC -GENERATION BULK SUPPLY',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14637',
        'PROJECT MANAGER - GENERATION',
        '11000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14720',
        'LEGAL SERVICES',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14815',
        'PROJECT MANAGER LUSIWASI HYDRO',
        '11000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14816',
        'PROJECT MANAGER CHIKOLOKI HYDRO',
        '11000',
        '01',
        '02');
INSERT INTO REF_COST_CENTERS
VALUES ('14817',
        'PROJECT MANAGER LUANGWA HYDRO',
        '11000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14818',
        'PROJECT MANAGER KNB EXTENSION',
        '14800',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14819',
        'PROJECT MANAGER SHIWANG''ANDU MINI HYDRO',
        '11000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12135',
        'CHIEF ENGINEER''S OFFICE',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12136',
        'CHIRUNDU',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12137',
        'MAPEPE',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12138',
        'FIG TREE',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12139',
        'MWANDI',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12140',
        'CHONGWE',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12141',
        'MAZABUKA',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12142',
        'LUSAKA WEST',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12143',
        'WATER WORKS',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13240',
        'DIESEL STATIONS',
        '13100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12285',
        'PREPAYMENT',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12290',
        'SYSTEMS OPERATIONS AND TRADING',
        '14900',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12171',
        'NAKAMBALA',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13280',
        'LUSAKA DIST-TRANS REHABILITAION PROJECT',
        '13200',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('11250',
        'Consultancy Services',
        '11200',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('11260',
        'SHEQ',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14900',
        'CHIEF OPERATING OFFICER',
        '14000',
        '00',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14520',
        'CHIEF OPERATING OFFICERS OFFICE',
        '14900',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12178',
        'Mpongwe West Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12179',
        'Chambeshi Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12180',
        'Chalabesa Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12181',
        'Ngoli Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12182',
        'Kateshi Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12183',
        'Lubushi Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12184',
        'Lubanseshi Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12185',
        'Mchinshi Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12186',
        'Reeves Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12187',
        'Saint Dorothy Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12188',
        'Kasempa T-Off Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12189',
        'Kyansununu Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12190',
        'Mwenda T-Off Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12191',
        'Maposa Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12192',
        'Ndeke Substation-Mufulira',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12193',
        'Mwambashi Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12194',
        'New Scaw Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12195',
        'Kasama Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12196',
        'Kalumbila Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12197',
        'Mansa Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('10001',
        'No Cost Center',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14121',
        'Procurement Local Department',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14122',
        'Procurement Foreign Department',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14123',
        'Procurement Contract Department',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14140',
        'Technical and Quality',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14141',
        'Health and Safety',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14142',
        'Compliance and Audits',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14143',
        'Environmental  Sustainability -North',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14144',
        'Environmental  Sustainability -South',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14145',
        'Business Risk Analyst',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14146',
        'Insurance',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14750',
        'Intellectual Property',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12222',
        'Chimetal 66/33KV Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14610',
        'ENGINEERING DEVELOPMENT',
        '14600',
        '00',
        '00');
INSERT INTO REF_COST_CENTERS
VALUES ('14630',
        'PRP',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13100',
        'DIVISION STRUCTURE',
        '13000',
        '00',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13200',
        'DISTRIBUTION & CUSTOMER SERVICES HEAD OFFICE',
        '13000',
        '00',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14100',
        'MANAGING DIRECTOR',
        '14000',
        '00',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14200',
        'FINANCE',
        '14000',
        '00',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14300',
        'HUMAN RESOURCES & ADMINISTRATION',
        '14000',
        '00',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14400',
        'CUSTOMER SERVICES',
        '13000',
        '00',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14500',
        'AUDIT SERVICES',
        '14000',
        '00',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14600',
        'ENGINEERING DEVELOPMENT',
        '14000',
        '00',
        '00');
INSERT INTO REF_COST_CENTERS
VALUES ('14700',
        'LEGAL SERVICES',
        '14000',
        '00',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13000',
        'DISTRIBUTION & SUPPLY',
        '10000',
        '00',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14000',
        'CORPORATE STRUCTURE',
        '10000',
        '00',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13230',
        'DISTRIBUTION CONTROL CENTER',
        '13200',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14316',
        'OCCUPATIONAL HEALTH',
        '14310',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14317',
        'HORTICULTURE',
        '14310',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14318',
        'SPORTS & SOCIAL CLUB',
        '14310',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14470',
        'INSPECTORATE AND SECURITY',
        '14700',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14623',
        'TRANSFORMER WORKSHOP',
        '15100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14621',
        'TRANSMISSION DEVELOPMENT',
        '14610',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14622',
        'MECHANICAL WORKSHOPS AND TRANSPORT',
        '14900',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('10000',
        'ZESCO',
        NULL,
        '00',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('11000',
        'GENERATION DEVELOPMENT',
        '10000',
        '00',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('11100',
        'GENERATION POWER STATIONS ',
        '11000',
        '00',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('11110',
        'STATION MANAGER',
        '11100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('11120',
        'CIVIL ',
        '11100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('11130',
        'OPERATIONS',
        '11100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('11140',
        'MAINTENANCE - DISTRIBUTION',
        '11100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('11150',
        'MECHANICAL',
        '11100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('11160',
        'STORES',
        '11100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('11200',
        'GENERATION DEVELOPMENT DIRECTORATE OFFICE',
        '11000',
        '00',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('11210',
        'GENERATION DEVELOPMENT DIRECTOR''S OFFICE',
        '11200',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('11220',
        'DIRECTORATE SUPPORT SERVICES',
        '11200',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('11230',
        'HYDROLOGY',
        '11200',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('11240',
        'CIVIL ENGINEERING SERVICES',
        '14900',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12000',
        'TRANSMISSION DEVELOPMENT',
        '10000',
        '00',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12100',
        'TRANSMISSION DEVELOPMENT SERVICES',
        '12000',
        '00',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12110',
        'SENIOR MANAGER''S OFFICE',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12111',
        'LINES',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12112',
        'LEOPARDS HILL',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12113',
        'CHIYAWA',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12114',
        'KAFUE GORGE',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12115',
        'KAFUE WEST',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12116',
        'ROMA',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12117',
        'KAFUE TOWN',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12118',
        'CONVENTRY',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12119',
        'MSORO',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12120',
        'LUSAKA WATER WORKS',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12121',
        'KARIBA NORTH BANK SUB STATION',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12122',
        'NAMPUNDWE',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12123',
        'KAZUNGULA',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12124',
        'MAAMBA',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12125',
        'MZUMA',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12126',
        'VICTORIA FALLS',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12127',
        'KATIMA MULILO',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12128',
        'KABWE',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12129',
        'PENSULO',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12130',
        'KAPIRI',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12131',
        'KABWE',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12132',
        'LUANO',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12133',
        'KITWE',
        '12100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12200',
        'ELECTRO TECHNICAL SERVICES ',
        '12000',
        '00',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12210',
        'SENIOR MANAGER''S OFFICE',
        '12200',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12220',
        'NATIONAL CONTROL CENTER',
        '12200',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12230',
        'SCADA',
        '12200',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12240',
        'TELECOMMS',
        '12200',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12250',
        'METERING',
        '12200',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12260',
        'PROTECTION',
        '12200',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12300',
        'TRANSMISSION DEVELOPMENT DIRECTORATE OFFICE',
        '12000',
        '00',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12310',
        'TRANSMISSION DEVELOPMENT DIRECTOR''S OFFICE',
        ' ',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12320',
        'SAFETY MANAGERS OFFICE',
        '12300',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12330',
        'ECONOMICS MANAGERS OFFICE',
        '12300',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12144',
        'KANSANSHI',
        '12000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12145',
        'MPONGWE',
        '12000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12146',
        'BWANAMKUBWA',
        '12000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14510',
        'AUDIT BUSINESS SUPPORT',
        '14100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13129',
        'CUSTOMER SERVICE CENTER',
        '13120',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13130',
        'TELECOMS',
        '13120',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('00000',
        'UNDEFINED',
        NULL,
        '01',
        '02');
INSERT INTO REF_COST_CENTERS
VALUES ('13110',
        'DIVISION MANAGER OFFICE',
        '13100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13121',
        'REGIONAL MANAGER OFFICES',
        '13120',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13122',
        'AREA/BRANCH MANAGER''S OFFICES',
        '13120',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13123',
        'PLANNING',
        '13120',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13124',
        'OPERATIONS & MAINTENANCE',
        '13120',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13125',
        'METER READING',
        '13120',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13126',
        'BILLING',
        '13120',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13127',
        'DEBT CONTROL',
        '13120',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13128',
        'INCIDENCE MANAGEMENT',
        '13120',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13210',
        'DISTRIBUTION & CUSTOMER SERVICES DIRECTOR''S OFFICE',
        '13200',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13220',
        'CONSTRUCTION',
        '13200',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14110',
        'MANAGING DIRECTOR''S OFFICE',
        '14100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14211',
        'FINANCE DIRECTOR''S OFFICE',
        '14210',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14212',
        'TREASURY & TAXATION',
        '14210',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14213',
        'TAXATION',
        '14210',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14214',
        'BUSINESS DEVELOPMENT',
        '15100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14215',
        'ACCOUNTING SERVICES',
        '14210',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14216',
        'INSURANCE & RISK MANAGEMENT',
        '14210',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14220',
        'ACCOUNTS BUSINESS SUPPORT',
        '11100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14311',
        'HUMAN RESOURCES & ADMINISTRATION DIRECTOR''S OFFICE',
        '14310',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14312',
        'RESOURCING, DEVELOPING & PERFORMANCE MGT',
        '14310',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14313',
        'PENSION MGT & INDUSTRIAL RELATIONS',
        '14310',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14314',
        'TRAINING CENTER',
        '14310',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14315',
        'SECURITY',
        '14700',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14320',
        'HUMAN RESOURCES BUSINESS SUPPORT',
        '14300',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14410',
        'CUSTOMER SERVICES DIRECTOR''S OFFICE',
        '14400',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14420',
        'CUSTOMER SERVICES',
        '14400',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14430',
        'MARKETING/PUBLIC RELATIONS',
        '14100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14440',
        'PUBLIC RELATIONS',
        '14400',
        '00',
        '02');
INSERT INTO REF_COST_CENTERS
VALUES ('14450',
        'INFORMATION & CYBER SECURITY SYSTEMS',
        '15100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14460',
        'PROTOCAL OFFICE',
        '14400',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14611',
        'ED DIRECTORS OFFICE',
        '14610',
        '01',
        '00');
INSERT INTO REF_COST_CENTERS
VALUES ('14612',
        'DISTRIBUTION DEVELOPMENT & PLANNING',
        '13200',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14613',
        'PROJECTS',
        '14610',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14614',
        'CONSTRUCTION',
        '14610',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14615',
        'PURCHASING AND SUPPLY CHAIN MANAGEMENT',
        '14900',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14616',
        'Procurement Support Services',
        '15100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14617',
        'FABRICATION WORKSHOPS',
        '15100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14618',
        'MECHANICAL  WORKSHOPS & TRANSPORT',
        '15100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14619',
        'ENVIRONMENT UNIT',
        '15100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14620',
        'TECHNICAL SUPPORT SERVICES',
        '15100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14631',
        'PROJECT MANAGER - TRANSMISSIONS',
        '11000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14632',
        'PROJECT MANAGER - GWEMBE-TONGA',
        '11000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14710',
        'LEGAL DIRECTOR & COMPANY SECRETARY',
        '14700',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13120',
        'REGIONAL STRUCTURE',
        '13100',
        '00',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14210',
        'FINANCE HEAD OFFICE',
        '14200',
        '00',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14310',
        'HUMAN RESOURCES & ADMINISTRATION HEAD OFFICE',
        '14300',
        '00',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12147',
        'LUMWANA',
        '12000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12148',
        'MUSHILI SUB STATION',
        '12000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12149',
        'MUKUMPU SUB STATION',
        '12000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12150',
        'KABWE CONVERTOR SUB STATION',
        '12000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12151',
        'MPIKA SUB STATION',
        '12000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12152',
        'CHINSALI SUB STAION',
        '12000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12153',
        'ISOKA SUB STATION',
        '12000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12154',
        'NAKONDE SUB STATION',
        '12000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12155',
        'KASAMA SUB STATION',
        '12000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12156',
        'MBALA SUB STATION',
        '12000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12157',
        'LUWINGU SUB STATION',
        '12000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12158',
        'KAWAMBWA SUB STATION',
        '12000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12159',
        'MPOROKOSO SUB STATION',
        '12000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12160',
        'SOLWEZI',
        '12000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12161',
        'LUSIWASI SUB STATION',
        '12000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12162',
        'CHISHIMBA SUB STATION',
        '12000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12164',
        'MUSONDA SUB STATION',
        '12000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12163',
        'LUNZUA SUB STATION',
        '12000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('9999',
        'TEST COST CENTER',
        NULL,
        '01',
        '02');
INSERT INTO REF_COST_CENTERS
VALUES ('12166',
        'MPOROKOSO',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12235',
        'CHIEF ENGINEER - SCADA OFFICE',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12245',
        'CHIEF ENGINEER - TELECOMS OFFICE',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12246',
        'TELECOMS - FIBRECOM',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12247',
        'FIBRECOM',
        '12200',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12255',
        'CHIEF ENGINEER - METERING OFFICE',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12265',
        'CHIEF PROTECTION ENGINEER - NORTH OFFICE',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12270',
        'CHIEF PROTECTION ENGINEER - SOUTH OFFICE',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12275',
        'CHIEF PROTECTION ENGINEER - GENERATION OFFICE',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('15111',
        'CABD DIRECTOR''S OFFICE',
        '15100',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12350',
        'TRANSMISSION SOUTH PROJECT MANAGER''S OFFICE',
        '12300',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12360',
        'TRANSMISSION NORTH PROJECT MANAGER''S OFFICE',
        '12300',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('15100',
        'BUSINESS DEVELOPMENT',
        '10000',
        '00',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14451',
        'ICT Security',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12219',
        'Luampa 66/11kv',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12221',
        'Mukuni 330/220kv',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14452',
        'Databases and Applications',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14453',
        'Data Center Management',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14454',
        'Networks and Operations',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14456',
        'Innovation and Systems Development',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14457',
        'Solution ,Optimization, Storage and Virtualization',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14800',
        'POWER REHUBILITATION POJECT(PRP)',
        '14000',
        '00',
        '00');
INSERT INTO REF_COST_CENTERS
VALUES ('14811',
        'PRP DIRECTOR''S OFFICE',
        '14800',
        '01',
        '00');
INSERT INTO REF_COST_CENTERS
VALUES ('14812',
        'PROJECT MANAGER KAFUE GORGE LOWER',
        '11000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14813',
        'PROJECT MANAGER ITT HYDRO',
        '11000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14814',
        'PROJECT MANAGER KAPISYA GEO',
        '11000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12280',
        'SUBSTATIONS',
        '12200',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12177',
        'CHAMBISHI SUBSTATION',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14120',
        'CORPORATE SPECIAL PROJECTS',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12172',
        'Chipata West Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12173',
        'Mumbwa Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12174',
        'ITT Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12175',
        'Lusaka Road Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12176',
        'Siavonga Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12306',
        '132/33/11kV Mufumbwe Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12311',
        'Mfuwe Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12312',
        'Azele Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12313',
        '66/33kV Mkushi Coppermine Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12314',
        '66/11kV Mwenge Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12315',
        '132/33/11kV Mwinilunga Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12316',
        '132/33/11kV Kabompo Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12317',
        'Lunsemfwa Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12318',
        '132/33/11kV Mufumbwe Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13318',
        'Chibombo Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13319',
        '66/33kV Chimsoro Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12261',
        'CCTV',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12262',
        'ETS Support Services',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12201',
        'Kanona Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12202',
        'SAFAL Switching Station',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12203',
        'PLR Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12204',
        'Mununga Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12205',
        'Mupepetwe Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12206',
        'Lusiwasi Compound Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12207',
        '66/33/11kV Kabwe Stepdown Annex Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12208',
        ' Regional Office',
        NULL,
        '01',
        '02');
INSERT INTO REF_COST_CENTERS
VALUES ('12209',
        'Nselauke Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12211',
        'St Dorothy Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12212',
        'Mwange T off',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12215',
        'Chambashitu Switching Station',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12216',
        'Chitope 132/33kV',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12217',
        'Mpanshya 132/33 kV',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12218',
        'Kalabo 66/33kv',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12109',
        'Kawambwa Tea Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12377',
        'Lusaka Mult Facility Economic Zone Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14458',
        'Enterprise Resource Planning -ERP',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12214',
        '66/11KV Simon Mwansa Kapwepwe INT Airport S/S',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12213',
        '132/66/33/11KV Kasompe Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('16116',
        'Social Services',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('16125',
        'Deputy Directors Office - Generation and Transmission Planning',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('16122',
        'Renewable Energy - Planning and Projects',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('16121',
        'Renewable Energy - Research',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('16131',
        'Generation Projects/Development',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('16136',
        'Transmission Projects/Development',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('16140',
        'Deputy Directors Office - Distribution Planning',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('16141',
        'Distribution Projects and System Studies',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('16130',
        'Generation Planning',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('16135',
        'Transmission Planning',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12395',
        'Regional Managers Office - Transmission',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13105',
        'Deputy Directors Office - Distribution and Customer Services - North Support Services',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13106',
        'Branch Managers Office',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13211',
        'Deputy Directors Office - Technical Services',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13212',
        'Deputy Directors Office - Distribution and Customer Services - South Support Services',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14115',
        'Head Procurement',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14219',
        'Deputy Director Treasury and Investments Office',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14221',
        'Deputy Director Accounting Services Office',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14225',
        'Head Business Development',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14226',
        'Contracts and Financial Analysis',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14228',
        'Performance Management',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14455',
        'Head ICT',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14505',
        'Head Audit and Risk Management',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14511',
        'Risk Audit',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14512',
        'Specialised Audit',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14513',
        'Financial and Operational Audits',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14711',
        'Deputy Directors Office - Company Secretarys Office',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('15112',
        'Deputy Directors Office - Corporate Support Services',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('15113',
        'Head - Security Services',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('15114',
        'Fixed Assets',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('15120',
        'Head - SHEQs Office',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('15121',
        'SHEQ Systems and Compliance Audits',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('15122',
        'SHEQ Operations',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('16110',
        'Directors Office - Planning and Projects',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('16115',
        'Head - Environment',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('16120',
        'Head - Renewable Energy',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12393',
        'Generation Networks Automation and Control',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12392',
        'Instrumentation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12391',
        'Maintenance Management',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12390',
        'Deputy Directors Office - Transmission Assets',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12388',
        'Energy Management and Dispatch Planning',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12387',
        'System Studies and Operations Planning',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12386',
        'Grid Code Management and Safety',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12385',
        'Deputy Directors Office - System Operations and Trade',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('11175',
        'Deputy Directors Office - Zambezi River Basin',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('11170',
        'Deputy Directors Office - Kafue River Basin',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12295',
        'TRADING',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13250',
        'CORPORATE REVENUE COLLECTION',
        '14400',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13260',
        'DISTRIBUTION REINFORCEMENT PROJECT',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14217',
        'BUDGETS, COMPLIANCE & INVESTMENTS',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14319',
        'HUMAN RESOURCES BUSINESS OPERATIONS',
        '14310',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14485',
        'LOSS REDUCTION',
        NULL,
        '01',
        '02');
INSERT INTO REF_COST_CENTERS
VALUES ('14490',
        'CALL CENTRE',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14730',
        'PROPERTY',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12167',
        'NSUMBU SUBSTATION',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12168',
        'LUANGWA KITWE SUBSTATION',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12169',
        'NCHELENGE SUBSTATION',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12170',
        'MBERESHI SUBSTATION',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14740',
        'Integrity Department',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13326',
        '330/66 kV  Nakonde Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13327',
        '66/33kV Serenje Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13328',
        '66/33kV Luangwa Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13329',
        '66/11kV  AGRO Scheme Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13330',
        '66/33kV  Mkushi Central',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12370',
        'Sioma Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12371',
        'Senanga Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12372',
        'Mongu Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12373',
        'Kaoma Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12374',
        '66/11KV Simungoma Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12375',
        '220/330KV Nambala & Lusaka West',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12376',
        'Lusaka Road Substation - Livingstone',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14129',
        'Procurement Documentation and Secretariat',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12379',
        'Kafue Gorge Lower 330kV S/S',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12380',
        'Chilanga 132/33/11Kv S/S',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12381',
        'KKIA 132/11Kv S/S',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12382',
        'Chawama 132/11Kv',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12383',
        'Kasompe Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12384',
        'Simon Mwansa Kapwepwe Intl Airport Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14633',
        'PROJECT MANAGER - KNBPS',
        '11000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14634',
        'PROJECT MANAGER-VFPC',
        '11000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14635',
        'PROJECT MANAGER-KGPS',
        '11000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14636',
        'PROJECT MANAGER-DISTRIBUTION',
        '11000',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14480',
        'DEMAND SITE MANAGEMENT',
        '14400',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12165',
        'NCC',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12340',
        'CHIEF ENGINEER''S OFFICE - NCC OFFICE',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14820',
        'NEW GENERATION PROJECTS',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14821',
        'LUNZUA POWER STATIONS',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14822',
        'MUSONDA FALLS POWER STATION',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14823',
        'CHISHIMBA FALLS POWER STATION',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14824',
        'CHINYUNYU GEOTHERMO',
        NULL,
        '01',
        '02');
INSERT INTO REF_COST_CENTERS
VALUES ('13270',
        'DISTRIBUTION PROJECTS',
        '13200',
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14218',
        'Investments',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13320',
        '132/33/11kV  Mumbeji Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13321',
        '132/33/11kV  Zambezi Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13322',
        '132/33/11kV  Chavuma  Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13323',
        '132/33/11kV  Lukulu Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13324',
        '330/66 kV  Mpika Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13325',
        '330/66 kV  Mansa Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('13331',
        'Sioma Substation',
        NULL,
        '01',
        '02');
INSERT INTO REF_COST_CENTERS
VALUES ('13332',
        'Senanga Substation',
        NULL,
        '01',
        '02');
INSERT INTO REF_COST_CENTERS
VALUES ('13333',
        'Mongu Substation',
        NULL,
        '01',
        '02');
INSERT INTO REF_COST_CENTERS
VALUES ('13334',
        'Kaoma Substation',
        NULL,
        '01',
        '02');
INSERT INTO REF_COST_CENTERS
VALUES ('13335',
        '66/11KV Simungoma Substation',
        NULL,
        '01',
        '02');
INSERT INTO REF_COST_CENTERS
VALUES ('13336',
        '220/330KV Nambala & Lusaka West',
        NULL,
        '01',
        '02');
INSERT INTO REF_COST_CENTERS
VALUES ('13337',
        'Lusaka Road Substation - Livingstone',
        NULL,
        '01',
        '02');
INSERT INTO REF_COST_CENTERS
VALUES ('14127',
        'E-Supply Chain Systems Department',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14128',
        'Bulk Stores and Warehousing Department',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12198',
        'Lusiwasi Upper Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12199',
        'Kabwe Sepdown 66/33/11kv Substation',
        NULL,
        '01',
        '02');
INSERT INTO REF_COST_CENTERS
VALUES ('14130',
        'Projects and Subsidiaries (Procurement)',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('14227',
        'Strategy',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('16132',
        'Senior Manager''s Office Generation Projects',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('16137',
        'Senior Manager''s Office Transmission Projects',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('16142',
        'Distribution Projects',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('16143',
        'Distribution Planning South',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('16144',
        'Distribution Planning North',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12223',
        'Annex (Kabwe) 66/33KV Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12224',
        'Champion 66/11KV Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12225',
        'OMAX Ferro Alloys 66/11KV Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12226',
        'AMAR Ferro Alloys 66/11KV Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12227',
        'United Alloys 66/11KV Substation',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12228',
        'Serenje Ferro 66/11KV',
        NULL,
        '01',
        '01');
INSERT INTO REF_COST_CENTERS
VALUES ('12229',
        'Substation Jasmin Alloys 66/11KV Substation',
        NULL,
        '01',
        '01');
