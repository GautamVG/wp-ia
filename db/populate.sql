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
        '$2y$10$XzSladyKL/5lWnM1iTnUzOrmnfzcvTxzuAYKUcOqiCWnsNXRPriN6', -- hellokitty
        1
    ),
    -- Ground Managers
    -- (
    --     'Abhinandan Goswami',
    --     'abhi.goswami',
    --     '$2y$10$W8xk4p8kE7sb8AjYtpXCL.8nunxrBOu.62aBYJlJiTI50OvE3mqTu', -- gogoswami
    --     2
    -- ),
    (
        'Pramod Dubey',
        'pramod.dubey',
        '$2y$10$126FLlbA1k6qFfTQaOJiG.CaaRPqfVPD02sGfeF.sZTPNUVsBdoYq',
        2
    ),
    -- Students
    (
        'Gautam Vishwakarma',
        'gautam.gv',
        '$2y$10$KiwLKW9opfnVcAH5dvFCNOhxxLKoBMET3opGaX02xhpO7dpbryru.', -- gautam123
        3
    ),
    -- (
    --     'Aditya Pai',
    --     'aditya.pai',
    --     '$2y$10$QK4Lq9.QhHi6lJR3DmC/s.RsCnnNWZ0fVUBOoCFJRUvtlY7OnlpnG', -- hailjesus
    --     3
    -- ),
    -- (
    --     'Chintan Shukla',
    --     'c.shukla',
    --     '$2y$10$sApKlFh.LbeFeIBroW.dne4sGi0/WNVaO.40z304V9TCKnlpj0P7y', -- purplelove
    --     3
    -- ),
    (
        'Harshal Dave',
        'harshal.dave',
        '$2y$10$iKwZq8564YSnCwE50sJovecYQQje8s16WVpXG3EiuqsBFsHU99qaK', -- roadyahinbanega
        3
    );