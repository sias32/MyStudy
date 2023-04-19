print('Задача 7. Почта')

# Почтовое отделение открывается в 08:00 и закрывается в 22:00.
# С 14:00 до 15:00 все сотрудники уходят на обед,
# а в 10:00 и 18:00 приезжают машины с посылками,
# и тогда все сотрудники на два часа заняты их разгрузкой.
# Во время обеда, разумеется, посылки никто не выдаёт,
# как и в моменты, когда разгружаются машины.

# Напишите программу,
# которая получает на вход время в часах — число от 0 до 23 — и пишет,
# можно ли в этот час получить посылку.
# Используйте только один условный оператор if-else, без elif и прочего.

# Решите задание двумя способами:

# первый — при выполнении условия выводится сообщение:
# «Можно получить посылку»,

# второй —  при выполнении условия выводится сообщение:
# «Посылку получить нельзя».

hours = int(input("Время, когда вы хотите придти (часы): "))

access = "Можно получить посылку"
deny = "Нельзя получить посылку"

# Рабочее время
if hours >= 8 and hours < 22:
  # Разгрузочное время
  if hours >= 10 and hours < 12:
    print(deny)
  # Разгрузочное время
  elif hours >= 18 and hours < 20:
    print(deny)
  # Обед
  elif hours >= 14 and hours < 15:
    print(deny)
  # Все остальное
  else:
    print(access)
else:
  print(deny)