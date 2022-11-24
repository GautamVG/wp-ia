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
        '04dac8afe0ca501587bad66f6b5ce5ad', -- hellokitty
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
    (
        'Chintan Shukla',
        'c.shukla',
        'd446a2e14b10db43f9b90dd46d85e76f', -- purplelove
        3
    ),
    (
        'Harshal Dave',
        'harshal.dave',
        '5f76f357b7b3158bbb7346c25e65c511', -- roadyahinbanega
        3
    );