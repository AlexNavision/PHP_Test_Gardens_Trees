<?php
/*
  Шабанов Александр. 11.01.2023. Тестовое задание PHP программист. Компания Оборот.ру
  Версии PHP: 8.1, 8.2
*/

/*
Архитектура

  enum TreeType: типы деревьев, вес и количество продукции с 1 дерева
  class Tree: абстрактный класс для всех деревьев
  class AppleTree, PearTree: классы наследники от Tree
  class Garden: Сад в котором растут деревья
  class App: старт основного сценария в Main()
  class UnitTests: тесты

🔥 Входные точки для старта - внизу программы.
   Все в одном файле, для удобства копирования. Вот хороший плейграуд -> https://www.phplayground.com/
*/

/*
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
*/


/*
  enum TreeType: типы деревьев, вес и количество продукции с 1 дерева
  class Tree: абстрактный класс для всех деревьев
  class AppleTree, PearTree: классы наследники от Tree
  class Garden: Сад в котором растут деревья
  class App
  class UnitTests: тесты

  🔥 Входные точки для старта - внизу программы.
*/

/* доступно с 8.1 */
enum TreeType: string
{
    case Apple = 'Яблоня';
    case Pear = 'Груша';

    /* 
        можно было переместить две функции ниже в класс 'Tree' в виде констант, но мне кажется такие значения должны приходить из БД или из вне.
        В любом случае для этого задания тут будет хардкод. Так хотя бы покажу как работают статические методы в enum (как и в обычном классе).
    */

    public static function GetTreeWeight(TreeType $type): int
    {
        return match($type)
        {
            TreeType::Apple => random_int(150, 180),
            TreeType::Pear => random_int(130,170)
        };
    }

    public static function GetTreeCount(TreeType $type): int
    {
        return match($type)
        {
            TreeType::Apple => random_int(40, 50),
            TreeType::Pear => random_int(0, 20)
        };
    }
}

abstract class Tree
{
    protected string $id;
    protected bool $isCollected;
    protected string $itemName,$name;
    protected TreeType $type;

    public function __construct()
    {
        $this -> isCollected = false;
        $this -> id = uniqid();
    }

    /* 
        для версии < 8.1 можно сделать => 
        switch {case: 'Apple' ...; break; case: 'Pear' ... ; break;}
    */
    public final static function CreateTree(TreeType $type): Tree
    {
        return match($type) 
        {
            TreeType::Apple => new AppleTree(),
            TreeType::Pear => new PearTree()  
        };
    }
    
    public function __toString() : string
    {
        return $this -> name;
    }
    public function GetItemName() : string
    {
        return $this -> itemName;
    }
    public function GetTypeOfTree() : string
    {
        return $this -> type->value;
    }

    public function IsCollected() : bool
    {
        return $this -> isCollected;
    }

    protected function Collect()
    {
        $this -> isCollected = true;
    }
    abstract function GetWeight(int $count) : int; //только для примера, по сути это избыточный код
    abstract function GetCount() : int; //только для примера, по сути это избыточный код
}

/*
    На самом деле можно было не создавать следующие 2 класса и сохранять (TreeType $type) в классе Tree. 
    Но сейчас допустим эти деревья будут осуществлять имплементацию методов GetWeight и GetCount, и у них будет разная инициализация в __construct
*/
final class AppleTree extends Tree
{
    public function __construct()
    {
        parent::__construct();
        $this -> name = 'Яблоня';
        $this -> itemName = 'Яблоко';
        $this -> type = TreeType::Apple; // просто для примера, можно было его передать в параметре...
    }

    public function Collect(): array
    {
        if ($this -> isCollected)
            return [];
        parent::Collect();
        $count = $this->GetCount();
        return ['key' => parent::GetItemName(), 'values' => ['weight' => $this->GetWeight($count), 'count' => $count]];
    }

    function GetWeight(int $count): int
    {
        $allweight = 0;
        for ($i=0; $i < $count; $i++) { 
            $allweight += TreeType::GetTreeWeight(TreeType::Apple);
        }
        return $allweight;
    }

    function GetCount(): int
    {
        return TreeType::GetTreeCount(TreeType::Apple);
    }
}

final class PearTree extends Tree
{
    public function __construct()
    {
        parent::__construct();
        $this -> name = 'Грушка';
        $this -> itemName = 'Груша';
        $this -> type = TreeType::Pear; // просто для примера, можно было его передать в параметре...
    }

    public function Collect(): array
    {
        if ($this -> isCollected)
            return [];
        parent::Collect();
        $count = $this->GetCount();
        return ['key' => parent::GetItemName(), 'values' => ['weight' => $this->GetWeight($count), 'count' => $count]];
    }

    function GetWeight(int $count): int
    {
        $allweight = 0;
        for ($i=0; $i < $count; $i++) { 
            $allweight += TreeType::GetTreeWeight(TreeType::Pear);
        }
        return $allweight;
    }

    function GetCount(): int
    {
        return TreeType::GetTreeCount(TreeType::Pear);
    }
}

interface iGarden
{
    public function AddTree(TreeType $type, int $count) : void;
    public function BeforeCollect(TreeType $type) : array; //[string data, ?string general_fault]
    function Collect(array $filtertree) : array; //[string data, ?string general_fault]
}

//Допустим что в этой задаче сад будет только один
final class Garden implements iGarden
{
    private array $trees; //child of Tree

    public function __construct()
    {
        $this -> trees = [];
        //echo $this -> trees;
    }
    public function AddTree(TreeType $type = null, int $count = null) : void
    {
        if (!is_null($type) && !is_null($count) && $count > 0)
            for ($i=0; $i < $count; $i++) 
            { 
                $this -> trees[] = Tree::CreateTree($type);
            }
        else 
            throw new Exception('Can\'t add new tree. Check Parameters');

    }
    /*
    * Пытаемся найти деревья
    */
    public function BeforeCollect(?TreeType $type = null,int $count = 0) : array
    {
        if (!is_null($type))
        {
            $filterTrees = array_filter($this->trees, function(AppleTree | PearTree $value) use($type) { return (!$value->isCollected()) && ($value->GetTypeOfTree() == $type->value);});
            if ($count > 0)
            {
                if (count($filterTrees) < $count || $count < 0)
                    return ["fault_error" => "Деревьев '{$type->value}' меньше чем {$count}, или число деревьев для сбора < 0"];
                return $this->Collect(array_slice($filterTrees,0,$count));
            }
            return $this->Collect($filterTrees);
        }
        else 
        {
            $filterTrees = array_filter($this->trees, function(AppleTree | PearTree $value) {return !$value->isCollected();} );
            return $this->Collect($filterTrees);
        }
    }
    /*
    * Собираем продукцию с выбранных деревьев
    */
    function Collect(array $filterTrees): array
    {
        //находим
        if (empty($filterTrees))
            return ["fault_error" => "Нет деревьев готовых для сбора"];

        //хотел сделать array_map, но тут это бесмысленно... лучше сразу сгруппировать по ключам
        //$filterTrees = array_map(function(AppleTree | PearTree $value) {return $value->Collect();} , $filterTrees

        //группируем
        $result = [];
        foreach ($filterTrees as &$filterTrees) 
        {
            //$filterTrees->Collect();
            $tmp = $filterTrees->Collect();
            if ($tmp != null)
              $result[$tmp["key"]][] = $tmp['values'];
        }

        //выводим результат
        $result_string = "Было собрано:";
        foreach ($result as $name => $items) 
        {
            $count = array_reduce($items, function($items,array $item){return $items += $item["count"];});
            $weight = array_reduce($items, function($items,array $item){return $items += $item["weight"];});
            $result_string .= "\n{$name}: {$count} шт, общим весом {$weight} гр";
        }

        return ["data" => $result_string];
    }
}


/*
    Класс только для тестов.  Не стал реализовывать большой модуль. Всего-лишь 2 метода по шаблону ААА, проверяющие ключи в результате. 
    По хорошему нужно сделать отдельный класс для проверки с многим количеством 'Тестовых дублей' и 'заглушек'
*/
class UnitTests
{
    //тесты
    private $errorsLog = [];
    /*
    * Будет хорошо, если запустить эти тесты несколько раз подряд
    */
    public function runTest()
    {
        $tests = 
        [
            "test1" => $this->Create_Trees_And_Collect_Production(...),
            "test2" => $this->Create_Trees_And_Collect_Spesific_Production(...),
        ];

        echo ("Начинаем UnitTests\n\n");
        $this->StartTest($tests);

        if (empty($this->errorsLog))
            echo ("\n\nТесты успешно пройдены");
        else 
            foreach ($this->errorsLog as $key => $value) {
                echo ("\nОшибка в тесте {$key}: {$value}\n");
            }
    }


    //точка входа для тестов.
    private function StartTest($tests)
    {
        foreach ($tests as $name => $test)
        {
            echo("Запуск теста {$name}\n");
            try
            {
                $test();
            }
            catch (Exception $e)
            {
                 $this->errorsLog[$name] = $e->getMessage();
            }
            finally
            {
                echo("Тест {$name} завершен\n");
            }
        }
    }
    //изолируем каждый тест, промежуточный и конечный результат во время работы не должен никуда сохраняться.

    /**
     * 1) Проверяем что мы можем добавить деревья и собрать с них что-то
     */
    private function Create_Trees_And_Collect_Production()
    {
        //[GIVEN] Главный сад
        $TestComponent = new Garden();

        //[GIVEN] Сажаем 10 яблонь и 15 груш
        $TestComponent->AddTree(TreeType::Apple,10);
        $TestComponent->AddTree(TreeType::Pear,15);

        //[WHEN] Собираем продукцию
        $result1 = $TestComponent->BeforeCollect();
        //[WHEN] Собираем продукцию 2 раз
        $result2 = $TestComponent->BeforeCollect();

        //[THEN] Должны собрать продукцию
        assert(!array_key_exists('data',$result1) || array_key_exists('fault_error',$result1), 'Hе смогли собрать продукцию');

        //[THEN] нельзя собрать 2 раз с тех же деревьев
        assert(!array_key_exists('fault_error',$result2) || array_key_exists('data',$result2), 'Смогли собрать продукцию 2 раз с тех же деревьев');
    }
    /**
     * 2) Проверяем что мы можем добавить 2 яблони и не сможем собрать 1 грушу или 3 яблони
     */
    private function Create_Trees_And_Collect_Spesific_Production()
    {
        //[GIVEN] Главный сад
        $TestComponent = new Garden();

        //[GIVEN] Сажаем 2 яблони
        $TestComponent->AddTree(TreeType::Apple,2);

        //[WHEN] Собираем 1 грушу
        $result1 = $TestComponent->BeforeCollect(TreeType::Pear,1);
        //[WHEN] Собираем 3 яблока
        $result2 = $TestComponent->BeforeCollect(TreeType::Apple,3);
        //[WHEN] Собираем все яблоки которые есть
        $result3 = $TestComponent->BeforeCollect(TreeType::Apple);

        //[THEN] нет груши
        assert(!array_key_exists('fault_error',$result1) || array_key_exists('data',$result1), 'Смогли собрать грушу с несуществующих деревьев');
        //[THEN] нет 3 яблок
        assert(!array_key_exists('fault_error',$result2) || array_key_exists('data',$result2), 'Смогли собрать больше яблок чем деревьев');
        //[THEN] Должны собрать яблоки
        assert(!array_key_exists('data',$result3) || array_key_exists('fault_error',$result3), 'Hе смогли собрать продукцию');
    }
}

/**
 * Точка входа в программу
 */
final class App
{
    /*
        Главный сценарий этой задачки
    */
    public static function Main()
    {
        $garden = new Garden();

        echo "Добавляем 10 яблонь и 15 груш\n";
        $garden->AddTree(TreeType::Apple,10);
        $garden->AddTree(TreeType::Pear,15);
        echo "Пытаемся собрать продукцию\n";
        self::Show($garden->BeforeCollect());
        echo "Пытаемся собрать продукцию еще раз\n";
        self::Show($garden->BeforeCollect());
        echo "Добавляем 5 яблонь\n";
        $garden->AddTree(TreeType::Apple,5);
        echo "Пытаемся собрать 1 яблоню\n";
        self::Show($garden->BeforeCollect(TreeType::Apple,1));
        echo "Пытаемся собрать 1 грушу\n";
        self::Show($garden->BeforeCollect(TreeType::Pear,1));
        echo "Все верно, мы добавили только 5 новых яблонь, а груши все уже собраны\n";
        echo "1 яблоню мы собрали их осталось 4. Попробуем собрать все что осталось\n";
        self::Show($garden->BeforeCollect());

        echo("\n\n\nТеперь запустим UnitTests \n\n");
    }
    /*
        просто вывод результата
    */
    public static function Show($arrayFromCollect)
    {
        if (array_key_exists('fault_error',$arrayFromCollect))
            echo "RESULT: " . $arrayFromCollect["fault_error"] . "\n\n";
        else 
            echo "RESULT: " . $arrayFromCollect["data"] . "\n\n";
    }
}


//Тестовое задание
App::Main();

//Тесты
$UnitTests = new UnitTests();
$UnitTests->runtest();
unset($UnitTests);
