import {Poker,Card} from 'poker'

const array_sum = (arr) => {
  return arr.reduce((total, current) => {
      return total + current;
  }, 0);
}
/**
 * 转换成点数
 * @param  cards 
 */
function toPoints(cards){
  const points = [];
  for (let i = 0; i < cards.length; i++) {
    const card = cards[i];
    if(card instanceof Card){
      //斗牛游戏中，10以上的都当10计算,包括大小王 
      points.push(card.getValue() > 10 ? 10:card.getValue())
    }
  }
  return points.length==5?points:[];
}
/**
 * 转换斗牛的值
 */
function pointsToTaurusValue(points){
  /**
   * 这里相当于复制
   */
  let tempArr = [];
  for (let i = 0; i < points.length; i++) {
    tempArr.push(points[i])
  }

  //0表示没牛
  let taurus = 0;
  let len = tempArr.length;
  for (let i = 0; i < len; i++) {
    let point1 = tempArr[i];
      if (taurus == 0 && i < len - 2) {
          for (let j = i + 1; j < len; j++) {
              if (taurus == 0) {
                let point2 = tempArr[j];
                  for (let k = j + 1; k < len; k++) {
                    let point3 = tempArr[k];
                      if ((point1 + point2 + point3) % 10 == 0) {
                        tempArr[i] = 0;
                        tempArr[j] = 0;
                        tempArr[k] = 0;
                          let sum = array_sum(tempArr) % 10;
                         
                          //这里的sum=10时，需要转换为10点,后续计算
                          taurus = sum == 0 ? 10 : sum;
                      }
                  }
              }
          }
      }
  }
  return taurus;
}

function extraRulefilter(taurusValue,points,minCard){
  let extraTaurusValue = 0;
  //五小牌: 五张牌加起来小于10,例如A, 3, 2, A, 2,即为五小牌.
  if (array_sum(points) <= 10) {
    taurusValue = 10;
    extraTaurusValue = 3;
  } //金牛: 所有牌都在J以上，包括J以上的牌，即最小的牌值大于、等于J的牌值11,例如: J, J, Q, Q, K, 即为金牛. 大小王也算
  else if (taurusValue == 10 && minCard.getValue() >= 11) {
      extraTaurusValue = 2;
  } //银牛: 所有牌都在10以上，包括J以上的牌，即最小的牌值大于、等于10,例如: 10, J, Q, K, K,即为银牛.
  else if (taurusValue == 10 && minCard.getValue() == 10) {
      extraTaurusValue = 1;
  }
  return taurusValue + extraTaurusValue;
}

export function Bullfight(){
  this.poker = new Poker(true);

  /**
   * 计算牛值
   * @param {*} oneHandCards 
   */
  this.calculate = function(oneHandCards){
    let minCard = this.poker.findTheMinCard(oneHandCards);
    let points = toPoints(oneHandCards)
   
    let taurusValue = pointsToTaurusValue(points);
    
    return extraRulefilter(taurusValue,points,minCard)
  }

  /**
   * 对比两手牌大小
   * @param {*} firstHandCards 
   * @param {*} secondHandCards 
   */
  this.compareHandCards = function(firstHandCards,secondHandCards){
    let firstTaurusValue = pointsToTaurusValue(toPoints(firstHandCards));
    let secondTaurusValue = pointsToTaurusValue(toPoints(secondHandCards));

    /**
     * 牛值一样的情况下，比最大的牌,牌型大小为:黑桃>红心>梅花>方块
     */
    if (firstTaurusValue == secondTaurusValue) {
        firstMaxCard = this.poker.findTheMaxCard(firstHandCards);
        return firstMaxCard.compareWith(this.poker.findTheMaxCard(secondHandCards));
    }
    return firstTaurusValue > secondTaurusValue ? 1 : -1;
  }

  /**
   * 将牛值转换成对应的名称
   * @param {*} taurusValue 
   */
  this.taurusValueToName = function(taurusValue){
    let name = '无牛';
    switch (taurusValue) {
      case 1:
      case 2:
      case 3:
      case 4:
      case 5:
      case 6:
      case 7:
      case 8:
      case 9:
        name = '牛' + taurusValue;
        break;
      case 10:
        name = '牛牛';
        break;
      case 11:
        name = '银牛';
        break;
      case 12:
        name = '金牛';
        break;
      case 13:
        name = '五小牌';
        break;
    }
    return name;
  }
}