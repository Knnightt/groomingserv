import { createStackNavigator } from '@react-navigation/stack';
import { ROUTES } from '../utils';

// screens
import Dashboard from '../screens/Dashboard';
import ProfileScreen from '../screens/ProfileScreen';

const Stack = createStackNavigator();

const MainNavigation = () => {
  return (
    <Stack.Navigator initialRouteName={ROUTES.DASHBOARD}>
      <Stack.Screen 
        name={ROUTES.DASHBOARD} 
        component={Dashboard} 
        options={{ headerShown: true, title: 'Dashboard' }}
      />
      <Stack.Screen 
        name={ROUTES.PROFILE} 
        component={ProfileScreen} 
        options={{ headerShown: true, title: 'Profile' }}
      />
    </Stack.Navigator>
  );
};

export default MainNavigation;