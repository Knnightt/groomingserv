import { Text, TouchableOpacity, View, Image } from 'react-native';

const MENU_ITEMS = [
  { key: 'Dashboard', icon: 'grid', label: 'Dashboard' },
  { key: 'Appointments', icon: 'calendar', label: 'Appointments' },
  { key: 'MyPets', icon: 'paw', label: 'My Pets' },
  { key: 'Profile', icon: 'user', label: 'Profile' },
  { key: 'Settings', icon: 'settings', label: 'Settings' },
];

const IconComponent = ({ name, color, size = 20 }) => {
  // Simple icon representation using text/shapes
  const icons = {
    grid: '⊞',
    calendar: '📅',
    paw: '🐾',
    user: '👤',
    settings: '⚙',
    logout: '→',
  };
  
  return (
    <Text style={{ fontSize: size, color, width: 24, textAlign: 'center' }}>
      {icons[name] || '•'}
    </Text>
  );
};

const Sidebar = ({ activeRoute, onNavigate, onLogout }) => {
  return (
    <View
      style={{
        width: 220,
        backgroundColor: '#FFFFFF',
        paddingVertical: 20,
        paddingHorizontal: 16,
        borderRightWidth: 1,
        borderRightColor: '#E5E5E5',
        height: '100%',
      }}
    >
      {/* Logo */}
      <View
        style={{
          flexDirection: 'row',
          alignItems: 'center',
          marginBottom: 30,
          paddingHorizontal: 8,
        }}
      >
        <View
          style={{
            width: 40,
            height: 40,
            borderRadius: 20,
            borderWidth: 2,
            borderColor: '#6B8BB8',
            alignItems: 'center',
            justifyContent: 'center',
            marginRight: 10,
          }}
        >
          <Text style={{ fontSize: 12, color: '#6B8BB8' }}>QGS</Text>
        </View>
        <View>
          <Text style={{ fontSize: 14, fontWeight: '600', color: '#6B8BB8' }}>
            Quibot's Grooming
          </Text>
          <Text style={{ fontSize: 12, color: '#6B8BB8' }}>Services</Text>
        </View>
      </View>

      {/* Menu Items */}
      <View style={{ flex: 1 }}>
        {MENU_ITEMS.map((item) => {
          const isActive = activeRoute === item.key;
          return (
            <TouchableOpacity
              key={item.key}
              onPress={() => onNavigate(item.key)}
              style={{
                flexDirection: 'row',
                alignItems: 'center',
                paddingVertical: 14,
                paddingHorizontal: 16,
                borderRadius: 8,
                backgroundColor: isActive ? '#6B8BB8' : 'transparent',
                marginBottom: 4,
              }}
            >
              <IconComponent
                name={item.icon}
                color={isActive ? '#FFFFFF' : '#333333'}
              />
              <Text
                style={{
                  marginLeft: 12,
                  fontSize: 14,
                  fontWeight: isActive ? '600' : '400',
                  color: isActive ? '#FFFFFF' : '#333333',
                }}
              >
                {item.label}
              </Text>
            </TouchableOpacity>
          );
        })}
      </View>

      {/* Logout */}
      <TouchableOpacity
        onPress={onLogout}
        style={{
          flexDirection: 'row',
          alignItems: 'center',
          paddingVertical: 14,
          paddingHorizontal: 16,
        }}
      >
        <IconComponent name="logout" color="#E57373" />
        <Text
          style={{
            marginLeft: 12,
            fontSize: 14,
            color: '#E57373',
            fontWeight: '500',
          }}
        >
          Log Out
        </Text>
      </TouchableOpacity>
    </View>
  );
};

export default Sidebar;
