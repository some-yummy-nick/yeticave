-- список объявлений
SELECT l.name, price, c.name "category" FROM lots l JOIN categories c ON l.category_id = c.id
