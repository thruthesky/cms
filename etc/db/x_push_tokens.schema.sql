

CREATE TABLE `x_push_tokens` (
  `token` varchar(255) NOT NULL,
  `user_ID` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `type` varchar(64) NOT NULL DEFAULT '',
  `stamp` int(10) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `x_push_tokens`
  ADD PRIMARY KEY (`token`),
  ADD KEY `user_ID` (`user_ID`),
  ADD KEY `type` (`type`),
  ADD KEY `stamp` (`stamp`);
COMMIT;
