INSERT INTO `user_type` (
    `label`
) VALUES 
    (
        'admin'
    ),
    (
        'ground_manager'
    ),
    (
        'student'
    );

INSERT INTO `ground` (
    `name` 
) VALUES 
    (
        'Marathon Track'
    ),
    (
        'Engineering Ground'
    ),
    (
        'Badminton Court'
    ),
    (
        'Chess Board'
    );

INSERT INTO `user` (
    `name`, 
    `svvid`, 
    `pwd`, 
    `type`
) VALUES 
    -- Admin User
    (
        'Administrator',
        'admin',
        '04dac8afe0ca501587bad66f6b5ce5ad',
        1
    ),
    -- Ground Managers
    -- (
    --     'Abhinandan Goswami',
    --     'abhi.goswami',
    --     'gogosami',
    --     2
    -- ),
    -- (
    --     'Pramod Dubey',
    --     'pramod.dubey',
    --     'dubeyji',
    --     2
    -- ),
    -- Students
    -- (
    --     'Gautam Vishwakarma',
    --     'gautam.gv',
    --     'hellokitty',
    --     3
    -- ),
    -- (
    --     'Aditya Pai',
    --     'aditya.pai',
    --     'hailjesus',
    --     3
    -- ),
    -- (
    --     'Chintan Shukla',
    --     'c.shukla',
    --     'purplelove',
    --     3
    -- ),
    -- (
    --     'Harshal Dave',
    --     'harshal.dave',
    --     'roadyahinbanega',
    --     3
    -- );