# PHP_Test_Gardens_Trees
  Версии PHP: 8.1, 8.2


Архитектура

  enum TreeType: типы деревьев, вес и количество продукции с 1 дерева
  class Tree: абстрактный класс для всех деревьев
  class AppleTree, PearTree: классы наследники от Tree
  class Garden: Сад в котором растут деревья
  class App: старт основного сценария в Main()
  class UnitTests: тесты

🔥 Входные точки для старта - внизу программы.
Все в одном файле, для удобства копирования. Вот хороший плейграуд -> https://www.phplayground.com/


Условия
    В саду посажано 10 яблонь и 15 груш; ✅
    С одной яблони можно собрать 40-50 яблок; ✅
    С одной груши можно собрать 0-20 груш; ✅
    У каждого дерева в саду есть уникальный регистрационный номер. ✅
Задача
    Реализовать, используя php, объектно-ориентированную систему: прототип сборщика фруктов.
    Система должна уметь:
        Добавлять деревья в сад; ✅
        Собирать плоды со всех деревьев, добавленных в сад; ✅
        Подсчитывать общее кол-во собранных плодов для каждого типа деревьев. ✅
        🔥 Добавил от себя -> Если мы собрали урожай с деревьев, то 2 раз этого сделать не сможем. Только если добавить новые деревья ✅
    *Необязательно к выполнению: 
        система может посчитать общий вес собранных фруктов каждого вида (1 яблоко весит 150 - 180 гр, 1 груша 130 - 170 гр) ✅
    Реализация графического интерфейса не требуется. ✅
    Способ инициализации деревьев в саду - на ваше усмотрение (массив/файл/база данных). ✅
        🔔 Добавил просто кодом:
            $MainComponent->AddTree(TreeType::Apple,10);
            $MainComponent->AddTree(TreeType::Pear,15);
    Результат запуска скрипта:
        При запуске скрипта main.php в консоли: 
        Система должна добавить деревья сад; ✅
        Произвести сбор продукции со всех деревьев; ✅
        Вывести на экран общее кол-во собранных фруктов каждого вида. ✅
        🔥 Вывести на экран общую массу собранных фруктов каждого вида ✅
Рекомендации: 
    Выполнять задачу ООП подходом, выделить объекты из задачи в классы ✅
    Написать юнит тесты на работу программы ✅
        🔥 Написал 2 юнит кейса на основную функциональность. Запускаются в UnitTests -> runTest✅

🔥 Попытался использовать современный enum (php >= 8.1)✅
🔥 Попытался использовать как больше методов, в частности такие как reduce,filter,map (php >= 8.1)✅
🔥 Использовать все основные принципы ООП: Инкапсуляция, Наследование, Абстракция, Полиморфизм ✅
🔥 Использовал замыкания  (use для фильтрации массива и $this->test1(...) для вызовов тестов) ✅
