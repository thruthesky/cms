
CREATE TABLE `x_verified_mobile_numbers` (
  `ID` int(10) UNSIGNED NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `stamp` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `x_verified_mobile_numbers`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `mobile` (`mobile`);

ALTER TABLE `x_verified_mobile_numbers`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;