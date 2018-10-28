new Ajax.PeriodicalUpdater(
        'itemphoto',
        'update.php?id=1',
        {
          method: 'get',
          frequency: 16,
        }
      )
      new Ajax.PeriodicalUpdater(
        'item1',
        'update.php?id=2',
        {
          method: 'get',
          frequency: 18,
        }
      )
      new Ajax.PeriodicalUpdater(
        'item2',
        'update.php?id=3',
        {
          method: 'get',
          frequency: 17,
        }
      )
      new Ajax.PeriodicalUpdater(
        'item3',
        'update.php?id=4',
        {
          method: 'get',
          frequency: 20,
        }
      )
      new Ajax.PeriodicalUpdater(
        'item4',
        'update.php?id=5',
        {
          method: 'get',
          frequency: 19,
        }
      )

