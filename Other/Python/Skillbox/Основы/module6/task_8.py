print('Задача 8. Игра «Компьютер угадывает число»')

# Поменяйте мальчика и компьютер из прошлой задачи местами.
# Теперь мальчик загадывает число между 1 и 100 (включительно).
# Компьютер может спросить у мальчика:
# «Твое число равно, меньше или больше, чем число N?»,
# где N — число, которое хочет проверить компьютер.
# Мальчик отвечает одним из трёх чисел:
# 1 — равно,
# 2 — больше,
# 3 — меньше.
 
# Напишите программу, 
# которая с помощью цепочки таких вопросов и ответов мальчика угадывает число.
 
# Дополнительно: сделайте так, чтобы можно было гарантированно угадать число за семь попыток.

# Подсказка: используйте бинарный поиск, а не конкатенацию.

while True:
  answer = int(input("Загадай число (0-100): "))

  if 0 < answer < 100:
    break
  print("Вы ввели неверно")


comp_num = 50
count = 0
divider = 2

while True:  
  if comp_num == answer:
    print(f"Значит ответ {comp_num}")
    break
    
  print(f"Ваше число: {comp_num} ?")
  human_answer = int(input("1 - равно, 2 - больше, 3 - меньше: "))

  if human_answer == 2:
    if count == 0:
      comp_num += 25
    elif count <= 3:
      comp_num += 25 // divider
      divider += 2
    else:
      comp_num += 1
  elif human_answer == 3:
    if count == 0:
      comp_num -= 25
    elif count <= 3:
      comp_num -= 25 // divider
      divider += 2
    else:
      comp_num -= 1
    count += 1
print(f"Ура, я угадал, попыток {count}")

# Вторая попытка
# start = 1
# finish = 101
# count = 1
# while True:
#     n = (start + finish) // 2
#     print('Загаданное число равно, меньше или больше', n)
#     answer = int(input('1 - равно, 2 - меньше, 3 - больше '))
#     if answer == 1:
#         print('Я угадал! Ура! с ', count, 'попытки')
#         break
#     elif answer == 2:
#         finish = n
#     elif answer == 3:
#         start = n
#     count += 1
# print('Может повторим?')
  