/**
 * Created by jamesskywalker on 04/03/2020.
 */
function testCase(a,b) {
    if(b) {
        switch(a){
        default:
            return 'jam';
        }
    }
    return a;

}

var one = 'test';

var two = 'test 2';

print(testCase(one,two));