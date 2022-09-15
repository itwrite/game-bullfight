
/**
 * 花色类型
 */
const CARD_TYPE_DIAMOND = 1,CARD_TYPE_CLUB=2,CARD_TYPE_HEART=3,CARD_TYPE_SPADE=4;
/**
 * 花色图标
 */
const icons = {};
icons[CARD_TYPE_DIAMOND] = '♦';
icons[CARD_TYPE_CLUB] = '♣';
icons[CARD_TYPE_HEART] = '♥';
icons[CARD_TYPE_SPADE] = '♠';

export function Card(name,value,type=CARD_TYPE_DIAMOND){
  let _name,_value,_type,_icon;
  this.setName = function(name){
    _name = name;
    return this
  }
  this.getName = function(){
    return _name
  }

  this.setValue = function(value){
    _value = value
    return this
  }
  this.getValue = function(){
    return _value
  }

  this.setType = function(type){
    _type = type;
    return this
  }
  this.getType = function(){
    return _type;
  }
  this.setIcon = function(icon){
    _icon = icon;
    return this;
  }
  this.getIcon = function(){
    return _icon;
  }

  this.compareWith = function(card){
    if(card instanceof Card){
      if(this.getValue() > card.getValue()){
        return 1;
      }else if(this.getValue() == card.getValue()){
        if(this.getType() > card.getType()){
          return 1
        }else if(this.getType() == card.getType()){
          return 0;
        }
      }
    }
    return -1;
  }

  this.toObject = function(){
    return {
      name:_name,
      value:_value,
      type:_type,
      icon:_icon
    }
  }

  this.setName(name);
  this.setValue(value);
  this.setType(type);
  this.setIcon(icons[type]||'');
}

function shuffle(arr){
  var l = arr.length
  var index, temp
  while(l>0){
      index = Math.floor(Math.random()*l)
      temp = arr[l-1]
      arr[l-1] = arr[index]
      arr[index] = temp
      l--
  }
  return arr
}

/**
 * ================================================
 * Pocker
 * ================================================
 */
//牌名
const cardNames = ['A', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];
//花色
const cardTypes = [CARD_TYPE_DIAMOND, CARD_TYPE_CLUB, CARD_TYPE_HEART, CARD_TYPE_SPADE];

export function Poker(hasKings = false){
  let _cards = [],_hasKings = hasKings;

  this.reset = function(){
    _cards = [];
    for(let i in cardNames){
      let value = parseInt(i) + 1;
      const name = cardNames[i];
      for(let j in cardTypes){
        const type = cardTypes[j]
        _cards.push(new Card(name,value,type));
      }
    }

    if(_hasKings == true){
      _cards.push(new Card('Joker',100,CARD_TYPE_CLUB))
      _cards.push(new Card('Joker',100,CARD_TYPE_SPADE))
    }
    return this;
  }

  this.doWash = function(){
    this.reset();
    _cards = shuffle(_cards);
  }

  this.getAllCards = function(){
    return this.toArray();
  }

  this.pop = function(){
    return _cards.pop();
  }

  this.findTheMaxCard = function(cards){
    if(cards.length > 0){
      let maxCard = cards[0]
      for(let i = 1;i<cards.length;i++){
        const card = cards[i]
        if(card instanceof Card && card.compareWith(maxCard) > 0){
          maxCard = card;
        }
      }
      return maxCard;
    }
    return null;
  }

  this.findTheMinCard = function(cards){
    if(cards.length > 0){
      let minCard = cards[0]
      for(let i = 1;i<cards.length;i++){
        const card = cards[i]
        if(card instanceof Card && card.compareWith(minCard) < 0){
          minCard = card;
        }
      }
      return minCard;
    }
    return null;
  }

  this.toArray = function(){
    const result = [];
    for (let i = 0; i < _cards.length; i++) {
      const element = _cards[i];
      if(element instanceof Card){
        result.push(element.toObject())
      }
    }
    return result;
  }
}