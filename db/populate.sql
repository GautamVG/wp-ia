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
        'Gautam Vishwakarma',
        'gautam.gv@somaiya.edu',
        '$2y$10$KiwLKW9opfnVcAH5dvFCNOhxxLKoBMET3opGaX02xhpO7dpbryru.', -- gautam123
        1
    ),
    -- Ground Managers
    -- (
    --     'Pramod Dubey',
    --     'pramod.dubey@somaiya',
    --     '$2y$10$126FLlbA1k6qFfTQaOJiG.CaaRPqfVPD02sGfeF.sZTPNUVsBdoYq',
    --     2
    -- ),
    (
        'Harshal Dave',
        'harshal.dave@somaiya.edu',
        '$2y$10$iKwZq8564YSnCwE50sJovecYQQje8s16WVpXG3EiuqsBFsHU99qaK', -- roadyahinbanega
        2
    ),
    -- Students
    (
        'Aditya Pai',
        'aditya.pai@somaiya.edu',
        '$2y$10$QK4Lq9.QhHi6lJR3DmC/s.RsCnnNWZ0fVUBOoCFJRUvtlY7OnlpnG', -- hailjesus
        3
    ),
    (
        'Chintan Shukla',
        'c.shukla@somaiya.edu',
        '$2y$10$sApKlFh.LbeFeIBroW.dne4sGi0/WNVaO.40z304V9TCKnlpj0P7y', -- purplelove
        3
    )
;

INSERT INTO `ground_to_user` (
    `user_svvid`,
    `ground_id` 
) VALUES 
    (
        'harshal.dave@somaiya.edu',
        1
    ),
    (
        'harshal.dave@somaiya.edu',
        2
    ),
    (
        'harshal.dave@somaiya.edu',
        3
    ),
    (
        'harshal.dave@somaiya.edu',
        4
    )
;