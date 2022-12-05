USE `zschedule_php`;

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

INSERT INTO `user` (
    `name`, 
    `photo`,
    `svvid`, 
    `pwd`, 
    `type`
) VALUES 
    -- Admin User
    (
        'Gautam Vishwakarma',
        '/public/upload/profile-pics/9f15cfc53ae49783b8c9dc7c0a9ef179.jpg',
        'gautam.gv@somaiya.edu',
        '$2y$10$KiwLKW9opfnVcAH5dvFCNOhxxLKoBMET3opGaX02xhpO7dpbryru.', -- gautam123
        1
    ),
    -- Ground Managers
    (
        'Pramod Dubey',
        '/public/upload/profile-pics/c87222ee85603e3c2b11435eae15d5a1.jpg',
        'pramod.dubey@somaiya.edu',
        '$2y$10$b0cCmRCIYaPfeG4ke7RCIenWXeELoBZT5xuva6eOanZyRpCn54cMG', -- dubeyji
        2
    ),
    (
        'Harshal Dave',
        '/public/upload/profile-pics/e488185f5b5d23f6981637885cb7d752.webp',
        'harshal.dave@somaiya.edu',
        '$2y$10$iKwZq8564YSnCwE50sJovecYQQje8s16WVpXG3EiuqsBFsHU99qaK', -- roadyahinbanega
        2
    ),
    -- Students
    (
        'Aditya Pai',
        '/public/upload/profile-pics/a900ed8d18444ea04c3c1a783fe4ac5c.jpg',
        'aditya.pai@somaiya.edu',
        '$2y$10$QK4Lq9.QhHi6lJR3DmC/s.RsCnnNWZ0fVUBOoCFJRUvtlY7OnlpnG', -- hailjesus
        3
    ),
    (
        'Chintan Shukla',
        '/public/upload/profile-pics/0fc14b1f858f1b6c24021dd1f13f5070.jpg',
        'c.shukla@somaiya.edu',
        '$2y$10$sApKlFh.LbeFeIBroW.dne4sGi0/WNVaO.40z304V9TCKnlpj0P7y', -- purplelove
        3
    ),
    (
        'Chandrashekhar Dubey',
        '/public/upload/profile-pics/6fa1be0d4dabfc158e39230eb3e2cd1d.png',
        'suraj.p@somaiya.edu',
        '$2y$10$P0rbd.r5Yx.YoNi2oXb5q.tZuqPOhWs707/AafFYZlhoASsgSJlvm', -- chatbot
        3
    ),
    (
        'Rati Shankar Shukla',
        '/public/upload/profile-pics/b996de93c5172853721baf35689fd02d.jpeg',
        'rati.shankar@somaiya.edu',
        '$2y$10$2rP/Hs7PZMtPETIWpckDRecVgLFJhF02nkMl7I4CPokEpRQguaks.', -- rati_123_shankar
        3
    )
;

INSERT INTO `ground` (
    `name`,
    `photo`,
    `manager_svvid`
) VALUES 
    (
        'Marathon Track',
        '/public/upload/grounds/b4950d389689c51bf579420f3544f895.jpg',
        'harshal.dave@somaiya.edu'
    ),
    (
        'Badminton Court',
        '/public/upload/grounds/73f86d031be77672c08d1e22f4570500.jpg',
        'harshal.dave@somaiya.edu'
    ),
    (
        'Chess Board',
        '/public/upload/grounds/91a953e0f76d407627420b1c0668324b.jpeg',
        'pramod.dubey@somaiya.edu'
    );

INSERT INTO `zone` (
    `name`,
    `is_primary`,
    `is_multi_zonal`,
    `amenities`,
    `ground_id`
) VALUES 
    (
        'Main Ground',
        true,
        true,
        NULL,
        1
    ),
    (
        'Running Track',
        false,
        NULL,
        '100m, 200m, 400m, 800, Stop watch, gun, flags',
        1
    ),
    (
        'Long jump',
        false,
        NULL,
        'There is a pit of sand in which you fall. That is it.',
        1
    ),
    (
        'Football',
        false,
        NULL,
        'Soccer ball, shin pads, air pump, sturds',
        1
    ),

    (
        'Badminton Court',
        true,
        true,
        'Indoor Badminton Court',
        2
    ),
    (
        'Professional Court',
        false,
        NULL,
        NULL,
        2
    ),
    (
        'Casual Court',
        false,
        NULL,
        NULL,
        2
    ),

    (
        'Chess Board',
        true,
        false,
        'Life size chessboard, cozy and quiet place, multiple regular sized chessboards and pieces available',
        3
    )
;