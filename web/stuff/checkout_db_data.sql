INSERT INTO `image` (`imageId`, `imageDomain`, `imageFolder`, `imageFile`, `imageWidth`, `imageHeight`, `imageCreationDate`) VALUES
(1, 'checkout.dev', 'type', 'sandw_incc.png', 45, 40, '2014-12-09 15:51:53'),
(2, 'checkout.dev', 'type', 'assiette_i2.png', 45, 40, '2014-12-09 15:51:54'),
(3, 'checkout.dev', 'type', 'hamberger.png', 45, 40, '2014-12-09 15:51:55'),
(4, 'checkout.dev', 'type', 'frit2.png', 45, 40, '2014-12-09 15:51:57'),
(5, 'checkout.dev', 'type', 'pilon.png', 45, 40, '2015-01-08 12:24:03'),
(6, 'checkout.dev', 'type', 'boisson_g.png', 45, 40, '2015-01-08 12:38:45'),
(7, 'checkout.dev', 'type', 'donot2.png', 45, 40, '2015-01-08 12:56:31');

INSERT INTO `type` (`typeId`, `typeName`, `type_imageId`) VALUES
(1, 'Sandwich', 1),
(2, 'Asiette', 2),
(3, 'Hamburger', 3),
(4, 'Barquette', 4),
(5, 'Poulet', 5),
(6, 'Boisson', 6),
(7, 'Dessert', 7);

INSERT INTO `product` (`productId`, `productName`, `productSauce`, `productMeat`, `productAdditional`, `productPrice`, `product_typeId`, `productMenu`, `product_imageId`) VALUES
(1, 'Tacos', 1, 1, 1, 5, 1, 1, NULL),
(2, 'Kebab', 1, 1, 1, 5, 1, 1, NULL),
(3, 'César', 1, 1, 1, 5, 1, 1, NULL),
(4, 'Américain', 1, 1, 1, 4.5, 1, 1, NULL),
(5, 'Escalope', 1, 1, 1, 5, 1, 1, NULL),
(6, 'Merguez', 1, 1, 1, 4.5, 1, 1, NULL),
(7, 'Maxi', 1, 1, 1, 3.5, 3, 1, NULL),
(8, 'Petit ham', 1, 1, 1, 2.5, 3, 1, NULL),
(9, 'Asiette Simple', 1, 1, 1, 8, 2, 1, NULL),
(10, 'Asiette double', 1, 1, 1, 10, 2, 1, NULL),
(11, 'Tenders', 1, 0, 0, 5, 5, 1, NULL),
(12, 'Chicken wings', 1, 0, 0, 3, 5, 1, NULL),
(13, 'Tiramisu', 0, 0, 0, 2.5, 7, 0, NULL),
(14, 'Tarte de dain', 0, 0, 0, 2, 7, 0, NULL),
(15, 'Petite Frite', 1, 0, 0, 1.5, 4, 0, NULL),
(16, 'Grande Frite', 1, 0, 0, 2, 4, 0, NULL);


INSERT INTO `sauce` (`sauceId`, `sauceName`, `sauceActive`) VALUES
(1, 'Harissa', 1),
(2, 'Algérienne', 1),
(3, 'Ketchup', 1),
(4, 'Marocaine', 1),
(5, 'Mayonnaise', 1),
(6, 'Blanche', 1),
(7, 'Barbecue', 1),
(8, 'Curry', 1),
(9, 'Tunisienne', 1),
(10, 'Cheezy', 1),
(11, 'Moutarde', 1),
(12, 'Fish', 1),
(13, 'Samourai', 1),
(14, 'Andalouse', 1),
(15, 'Burger', 1),
(16, 'Tartar', 1);

INSERT INTO `menu` (`menuId`, `menuActive`, `menu_productId`) VALUES
(1, 1, 12),
(2, 1, 15);

INSERT INTO `addtional` (`additionalId`, `additionalName`, `additionalPayant`, `additionalPrice`) VALUES
(1, 'Fromage Carré', 1, 0.5),
(2, 'Fromage Rappé', 1, 0.5),
(3, 'Olives', 0, NULL),
(4, 'Salade', 0, NULL),
(5, 'Tomate', 0, NULL),
(6, 'Oignon', 0, NULL);

INSERT INTO `admin` (`adminId`, `adminActive`, `adminEmail`, `adminFirstName`, `adminLastName`, `adminPhone`, `adminGender`, `adminPassword`, `adminSalt`, `adminCreationDate`, `adminRole`) VALUES
(1, 1, 'charfeddine.mahmoud@gmail.com', 'mahmoud', 'mahmoud', '0614243663', 'm', 'Az2D0B1xIk7+4xX1GqsMdcpVu19tVNIBk+M6Z+WEAQjZ3jhy83FtsXuLom40BwZdP8XPXNU0XZ8+bA9xVe9kvQ==', 'cjezptqt56a20c64dc9ca', '2014-12-11 00:00:00', 'ADMIN');

INSERT INTO `meat` (`meatId`, `meatName`, `meatActive`) VALUES
(1, 'Escalope', 1),
(2, 'Steack', 1),
(3, 'Kebab', 1),
(4, 'Nugettes', 1),
(5, 'Cordon bleu', 1),
(6, 'Tenders', 1),
(7, 'Thon', 1),
(8, 'Merguez', 1);