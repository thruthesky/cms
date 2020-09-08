
CREATE TABLE `x_like_log` (
  `idx` int(10) UNSIGNED NOT NULL,
  `post_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `choice` varchar(7) NOT NULL,
  `stamp` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `x_like_log`
  ADD PRIMARY KEY (`idx`),
  ADD UNIQUE KEY `post_user` (`post_id`,`user_id`);

ALTER TABLE `x_like_log`
  MODIFY `idx` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
